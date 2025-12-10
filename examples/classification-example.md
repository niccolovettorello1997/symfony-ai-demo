POST http://localhost:8080/classify

*Request*

```json
{
    "text": "Love Symfony AI!",
    "labels": [
        "Positive",
        "Negative",
        "Neuter"
    ]
}
```

*Response*

```json
{
    "reply": "Positive"
}
```
