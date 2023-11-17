# PayPay の送金 QR を表示するシステム。

## 用途

- kakera 主催のイベントの集金を少し便利にするためのシステム。
- Zenn の記事: https://zenn.dev/kakera_lab/articles/59a067e3fc305f
- 関連記事: https://zenn.dev/kakera_lab/articles/f74c068e67a600
- 関連記事: https://github.com/kakera-lab/nfc_cardtrade

## 中身

- PayPay で発行できる送金用 QR コードの画像を金額に応じて表示するプログラム。
- Python と PaSoRi を使って NFC カードを読み込むプログラムを含む。
- 集金状況を確認するページ
- `自分のサイトのURL>?summary=1`としてアクセスする。

## 現在の実装の処理

## リポジトリの構成

```
/
├- /python: NFCをPCで読み込むためのプログラム
└- /php: サーバーに配置するプログラム
```
