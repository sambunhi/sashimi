# sashimi

這是一個雲原生關鍵字聲量偵測系統，可以設定要關注的關鍵字。

## To Do

- [ ] 關鍵字，一組多詞、多語系（ex. COV-19, COVID-19, 新冠肺炎）

## Development Environment

(Assuming composer packages are already installed)

```sh
env PHP="$(yq .env.PHP .github/workflows/ci.yml)" \
    PHP_ALPINE_DEPS="$(yq .env.PHP_ALPINE_DEPS .github/workflows/ci.yml)" \
    docker-compose up
```
