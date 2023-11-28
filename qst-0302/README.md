## 3-2. Virtual Hostの設定
Nginxのコンテナを1つ利用してDockerでWebサーバーを構築してください。
この時Vritual Hostを設定し`http://aaa.example.com`と`http://bbb.example.com`にアクセスするとそれぞれ別のコンテンツが表示されるようにしてください。

[バーチャルホスト](https://ja.wikipedia.org/wiki/%E3%83%90%E3%83%BC%E3%83%81%E3%83%A3%E3%83%AB%E3%83%9B%E3%82%B9%E3%83%88)

本問の解答には`ans-0302`というディレクトリを作成し、その中でディレクトリやファイルを作成してください。

## 目次
- [3-2. Virtual Hostの設定](#3-2-virtual-hostの設定)
- [目次](#目次)
- [構成図](#構成図)
- [要件](#要件)
  - [コンテナの起動](#コンテナの起動)
  - [ドメインとIPアドレスの対応](#ドメインとipアドレスの対応)

## 構成図

```mermaid
graph LR
   Browser[Web Browser]

   subgraph  Docker
      subgraph "Nginx Docker Container"
         aaa["Website A"]
         bbb["Website B"]
      end
   end

   Browser --> |http://aaa.example.com| aaa
   Browser --> |http://bbb.example.com| bbb
```

## 要件
- Nginxのイメージは[公式のもの](https://hub.docker.com/_/nginx)を使ってください
- Nginxのイメージは`latest`タグを使ってください
- 解答には`Dockerfile`や`docker-compose.yml`などコンテナを起動するために必要なファイルを含めてください
- 環境を構築するにあたって、必要なら他のファイルを作成しても構いません
- 環境の構築にあたりNginxのコンテナは1つだけ利用してください
- `docker-compose up`を実行することでコンテナを起動できるようにしてください
- `http://aaa.example.com`にブラウザでアクセスすると`aaa.example.com`というテキストが表示されるようにしてください
  - `aaa.example.com`というテキストは`/usr/share/nginx/aaa/index.html`に記載してください
- `http://bbb.example.com`にブラウザでアクセスすると`bbb.example.com`というテキストが表示されるようにしてください
  - `bbb.example.com`というテキストは`/usr/share/nginx/bbb/index.html`に記載してください
- Virtual Hostの設定は`/etc/nginx/conf.d/default.conf`に記載してください

上記の説明とディレクトリ構成をまとめると以下のようになります。

```
/
├─/etc
│ └─/nginx
│    └─/conf.d
│       └─/default.conf ... Virtual Hostの設定
└─/usr
    └─/share
       └─/nginx
          ├─/aaa
          │ └─/index.html ... "aaa.example.com"を記載する
          └─/bbb
             └─/index.html ... "bbb.example.com"を記載する
```

### コンテナの起動
コンテナの起動にはシェルスクリプトを記述したファイルを作成し、それを実行するようにしてください。
例えばコンテナ起動のためのスクリプト`docker-up.sh`を作成し、その中に以下のような内容を記述します。

```
#!/bin/bash
docker compose up --build
```

実行権限を付与します。
```
chmod +x ./docker-up.sh
```

そして`./docker-up.sh`を実行することでコンテナが起動できるようになります。

### ドメインとIPアドレスの対応
`aaa.example.com`と`bbb.example.com`にアクセスするにはドメインとIPアドレスの対応を設定する必要があります。
Unix/Linuxの場合、エディタで`/etc/hosts`を開いて以下を追記してください。(`sudo`が必要な場合があります)

```
127.0.0.1 aaa.example.com bbb.example.com
```

検証が終了した場合は`/etc/hosts`から追記した行を削除してください。
