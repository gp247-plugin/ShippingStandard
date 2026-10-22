> 🌐 **言語:** [🇻🇳 Tiếng Việt](./readme_vi.md) · [🇬🇧 English](./readme.md) · 🇯🇵 日本語（現在）

## 概要
GP247/Shop 向けの標準配送プラグインです。注文の合計金額に基づいて送料を計算し、設定したしきい値に達すると自動で送料無料になります。

- 要件：GP247/Shop（Core 3.0 または 3.1）、`gp247/shop` パッケージがインストールされ稼働していること。
- バージョン：2.4.2

## 機能紹介
- 商品の合計金額に応じて送料を計算します。
- 合計金額がしきい値 `shipping_free` に達すると送料無料になります。
- プラグインの `config.php` ファイルで柔軟に設定できます。
- **マルチストア／マーケットプレイスのサイトではストアごとに設定可能**：各ストアが自分でプラグインを有効にし、独自の `fee`／送料無料しきい値を持てます。上書きするまでは共通設定を継承します（`storeScope: store`）。シングルストアのサイトには影響しません。
- **`gp247/shop` の標準配送レイヤー上で動作**（2.4.0 以降、このレイヤーを持つバージョンの shop の場合）：送料は配送ステップで他の配送会社と一緒に見積もられ、お客様が注文した時点の金額が保持され、表示中の通貨に正しく換算されます。それより古い `gp247/shop` では、プラグインは従来どおり動作します。
- プラグインの表示確認用のフロントのルートと、管理用の admin ルートを提供します：`plugin/shippingstandard/index`、`GP247_ADMIN_PREFIX/shippingstandard`。

## インストール方法
次のいずれかの方法でインストールできます。

1) オンラインインストール
- 管理画面でプラグインライブラリを開きます。
- ShippingStandard プラグインを検索してインストールします。

2) .zip ファイルからのインストール
- プラグインの .zip パッケージをダウンロードします。
- 管理画面で .zip パッケージをインポートすると、システムが自動でインストールします。

3) 手動インストール（開発者向け）
- プラグインパッケージを解凍します。
- `app/GP247/Plugins/ShippingStandard` フォルダーにコピーします。
- 管理画面にログインし、Extensions エリアで確認して有効化します。

4) コマンドラインでのインストール（CLI、gp247 3.x）

gp247 3.x からは、管理画面を開かずに、GP247 ライブラリから **ShippingStandard** をダウンロードしてコマンドラインですぐにインストールできます。サイトのルートフォルダーでターミナルを開き、次を実行します。

```bash
# 1) ウェブサイトごとに 1 回だけ：GP247 ライブラリに接続するための API License（無料）を登録
php artisan gp247:ext-register-license

# 2) ライブラリからプラグインをダウンロードしてインストール
php artisan gp247:ext-install --type=plugin --key=ShippingStandard
```

- 手順 1 の前に、`.env` の `APP_URL` がサイトの**実際のドメイン**になっていることを確認してください（`http://localhost` のままにしないでください）。ライセンスはこのドメインに紐づけられます。
- インストールが完了すると、プラグインは**有効化済み**の状態になり、キャッシュも自動で更新されます。管理画面で追加の操作は必要ありません。
- コマンドは `gp247.json` で宣言された条件（コアのバージョン、composer パッケージ、依存プラグイン）を自動でチェックします。不足があればコマンドは停止し、何が足りないかを明示します。
- このプラグインには `gp247/shop` パッケージのインストールが必要です。不足している場合、コマンドは停止して通知します。
- `app/GP247/Plugins/ShippingStandard` フォルダーがすでにサーバー上にある場合（手動でコピーした、またはインストールパッケージに同梱されていた場合）、コマンドは再ダウンロードせず**その場でインストール**します。
- プラグインがすでにインストール済みの場合、コマンドは実行を拒否します。新しいバージョンに更新するには `php artisan gp247:ext-update --type=plugin --key=ShippingStandard` を実行します。
- コマンドの末尾に `--json` を付けると、機械可読な形式で結果を受け取れます（スクリプト／CI 用）。
- 下記の**使い方**の手順はそのまま有効です：手順 1（有効化）はコマンドで完了済みですが、手順 2（`fee`／`shipping_free` の設定）と手順 3（連携の確認）は引き続き行う必要があります。
- 詳細：[プラグイン＆テンプレートのインストールガイド](https://github.com/gp247net/gp247-docs/blob/main/extension/install-extension.md) · [コマンドリファレンス](https://github.com/gp247net/gp247-docs/blob/main/system/command-line-reference.md)。

## 使い方
1) プラグインの有効化
- 管理画面 > Extensions > Shipping で「ShippingStandard」プラグインを有効にします。

2) パラメーターの設定
- `app/GP247/Plugins/ShippingStandard/config.php` ファイルで編集します：

```php
<?php
return [
    'fee' => 20, // Base shipping fee
    'shipping_free' => 10000, // Free shipping threshold (subtotal >= value)
];
```

- 設定のヒント：
  - 常に送料をかけたい（送料無料を適用しない）場合は、`shipping_free = 0` に設定します。
  - 配送ポリシーに合わせて `fee` を増減します。

3) 連携の確認
- チェックアウトの流れの中で、プラグインは小計（subtotal）に基づいて送料を自動計算します。`plugin/shippingstandard/index` にアクセスすると、プラグインのフロント画面を確認できます。

## ドキュメント
- GitHub リンク：`https://github.com/gp247net/ShippingStandard`
- ガイドリンク：`https://gp247.net/en/docs/user-guide-extension/guide-to-installing-the-extension.html`

## ライセンス
GP247 が開発しています。
