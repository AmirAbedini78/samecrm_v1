#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
LSTM-based demand forecast for SameCRM.
Reads JSON time series, trains a small LSTM, returns next-period predictions.

Usage:
  python tools/lstm_forecast.py --input path/to/series.json [--steps 4] [--lookback 8]

JSON input: {"series": [v1, v2, ...]} or {"values": [v1, v2, ...]}
Output (stdout): JSON with "success", "predictions", "method" ("lstm" or "fallback").
"""

from __future__ import print_function, unicode_literals

import argparse
import json
import os
import sys

import numpy as np

HAS_KERAS = False
try:
    import tensorflow as tf
    from tensorflow import keras
    from tensorflow.keras import layers
    HAS_KERAS = True
except ImportError:
    pass


def load_series(path):
    with open(path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    series = data.get('series') or data.get('values') or data.get('weekly_totals')
    if isinstance(series, list) and series and isinstance(series[0], dict):
        series = [float(x.get('value', x.get('amount', 0))) for x in series]
    return [float(x) for x in series]


def build_sequences(series, lookback):
    X, y = [], []
    for i in range(lookback, len(series)):
        X.append(series[i - lookback:i])
        y.append(series[i])
    return np.array(X), np.array(y)


def forecast_lstm(series, steps=4, lookback=8, epochs=30):
    if len(series) < lookback + 5:
        return None, "need at least {} points".format(lookback + 5)
    series = np.array(series, dtype=np.float64)
    if np.any(np.isnan(series)):
        series = np.nan_to_num(series, nan=0.0)
    min_val, max_val = series.min(), series.max()
    if max_val - min_val < 1e-9:
        return [float(series[-1])] * steps, "constant"
    norm = (series - min_val) / (max_val - min_val + 1e-9)
    X, y = build_sequences(norm.tolist(), lookback)
    X = X.reshape(X.shape[0], X.shape[1], 1)
    model = keras.Sequential([
        layers.LSTM(16, activation='relu', input_shape=(lookback, 1)),
        layers.Dense(8, activation='relu'),
        layers.Dense(1),
    ])
    model.compile(optimizer='adam', loss='mse')
    model.fit(X, y, epochs=epochs, batch_size=min(32, len(X)), verbose=0)
    last = norm[-lookback:].reshape(1, lookback, 1)
    preds = []
    for _ in range(steps):
        p = model.predict(last, verbose=0)[0, 0]
        preds.append(float(p * (max_val - min_val) + min_val))
        last = np.roll(last, -1, axis=1)
        last[0, -1, 0] = p
    return preds, "lstm"


def forecast_fallback(series, steps=4):
    """Simple exponential smoothing when Keras not available."""
    a = 0.3
    s = series[0]
    for v in series[1:]:
        s = a * v + (1 - a) * s
    trend = (series[-1] - series[0]) / max(len(series) - 1, 1)
    return [max(0, s + trend * (i + 1)) for i in range(steps)], "fallback"


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True, help='Path to JSON file')
    parser.add_argument('--steps', type=int, default=4)
    parser.add_argument('--lookback', type=int, default=8)
    args = parser.parse_args()
    if not os.path.isfile(args.input):
        out = {'success': False, 'error': 'File not found', 'predictions': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 1
    try:
        series = load_series(args.input)
    except Exception as e:
        out = {'success': False, 'error': str(e), 'predictions': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 2
    if len(series) < 10:
        out = {'success': False, 'error': 'Need at least 10 data points', 'predictions': [], 'method': None}
        print(json.dumps(out, ensure_ascii=False))
        return 3
    if HAS_KERAS:
        try:
            preds, method = forecast_lstm(series, steps=args.steps, lookback=min(args.lookback, len(series) - 5))
            if preds is None:
                out = {'success': False, 'error': method, 'predictions': [], 'method': 'lstm'}
            else:
                out = {'success': True, 'predictions': [round(p, 2) for p in preds], 'method': method}
            print(json.dumps(out, ensure_ascii=False))
            return 0
        except Exception as e:
            preds, _ = forecast_fallback(series, args.steps)
            out = {'success': True, 'predictions': [round(p, 2) for p in preds], 'method': 'fallback', 'keras_error': str(e)}
            print(json.dumps(out, ensure_ascii=False))
            return 0
    else:
        preds, _ = forecast_fallback(series, args.steps)
        out = {'success': True, 'predictions': [round(p, 2) for p in preds], 'method': 'fallback', 'note': 'Install tensorflow for LSTM'}
        print(json.dumps(out, ensure_ascii=False))
        return 0


if __name__ == '__main__':
    sys.exit(main())
