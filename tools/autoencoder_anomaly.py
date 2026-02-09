#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Autoencoder-based anomaly detection for daily sales.
Reads JSON of daily stats, fits autoencoder, returns anomaly scores and flagged days.

Usage:
  python tools/autoencoder_anomaly.py --input path/to/days.json [--threshold 2.0]

JSON input: {"days": [{"date": "Y-m-d", "amount": x, "count": n}, ...]}
Output (stdout): JSON with "success", "anomalies", "method", "stats".
"""

from __future__ import print_function, unicode_literals

import argparse
import json
import os
import sys

import numpy as np

HAS_KERAS = False
try:
    from tensorflow import keras
    from tensorflow.keras import layers
    HAS_KERAS = True
except ImportError:
    pass

try:
    from sklearn.neural_network import MLPRegressor
    from sklearn.preprocessing import StandardScaler
    HAS_SKLEARN = True
except ImportError:
    HAS_SKLEARN = False


def load_days(path):
    with open(path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    days = data.get('days') or data.get('data') or []
    dates = []
    features = []
    for d in days:
        date = d.get('date') or d.get('day')
        amount = float(d.get('amount') or d.get('total_amount') or 0)
        count = float(d.get('count') or d.get('order_count') or 0)
        dates.append(date)
        features.append([amount, count] if count else [amount, amount * 0.01])
    return dates, np.array(features, dtype=np.float64)


def fit_autoencoder_keras(X, encoding_dim=2, epochs=50):
    n_features = X.shape[1]
    input_dim = n_features
    encoder = keras.Sequential([
        layers.Dense(8, activation='relu', input_shape=(input_dim,)),
        layers.Dense(encoding_dim, activation='relu'),
    ])
    decoder = keras.Sequential([
        layers.Dense(8, activation='relu', input_shape=(encoding_dim,)),
        layers.Dense(input_dim),
    ])
    autoencoder = keras.Sequential([encoder, decoder])
    autoencoder.compile(optimizer='adam', loss='mse')
    autoencoder.fit(X, X, epochs=epochs, batch_size=min(16, len(X)), verbose=0)
    pred = autoencoder.predict(X, verbose=0)
    errors = np.mean((X - pred) ** 2, axis=1)
    return errors


def fit_autoencoder_sklearn(X):
    scaler = StandardScaler()
    Xs = scaler.fit_transform(X)
    # bottleneck: 2 neurons
    model = MLPRegressor(hidden_layer_sizes=(8, 2, 8), max_iter=200, random_state=42)
    model.fit(Xs, Xs)
    pred = model.predict(Xs)
    errors = np.mean((Xs - pred) ** 2, axis=1)
    return errors


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True, help='Path to JSON file')
    parser.add_argument('--threshold', type=float, default=2.0, help='Z-score threshold for anomaly')
    args = parser.parse_args()
    if not os.path.isfile(args.input):
        out = {'success': False, 'error': 'File not found', 'anomalies': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 1
    try:
        dates, X = load_days(args.input)
    except Exception as e:
        out = {'success': False, 'error': str(e), 'anomalies': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 2
    if len(dates) < 10:
        out = {'success': False, 'error': 'Need at least 10 days', 'anomalies': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 3
    if np.any(np.isnan(X)):
        X = np.nan_to_num(X, nan=0.0)
    method = 'fallback'
    if HAS_KERAS:
        try:
            errors = fit_autoencoder_keras(X)
            method = 'autoencoder_keras'
        except Exception:
            if HAS_SKLEARN:
                errors = fit_autoencoder_sklearn(X)
                method = 'autoencoder_sklearn'
            else:
                errors = np.zeros(len(X))
                method = 'fallback'
    elif HAS_SKLEARN:
        errors = fit_autoencoder_sklearn(X)
        method = 'autoencoder_sklearn'
    else:
        mean = np.mean(X, axis=0)
        errors = np.mean((X - mean) ** 2, axis=1)
        method = 'fallback'
    mean_err = np.mean(errors)
    std_err = np.std(errors) or 1e-9
    z_scores = (errors - mean_err) / std_err
    anomalies = []
    for i, (d, z) in enumerate(zip(dates, z_scores)):
        if abs(z) >= args.threshold:
            anomalies.append({
                'date': d,
                'z_score': round(float(z), 2),
                'reconstruction_error': round(float(errors[i]), 4),
                'reason': 'فروش بسیار بالا' if z > 0 else 'فروش بسیار پایین / الگوی غیرعادی',
            })
    out = {
        'success': True,
        'anomalies': anomalies,
        'method': method,
        'stats': {'mean_error': round(float(mean_err), 4), 'std_error': round(float(std_err), 4), 'threshold': args.threshold},
    }
    print(json.dumps(out, ensure_ascii=False))
    return 0


if __name__ == '__main__':
    sys.exit(main())
