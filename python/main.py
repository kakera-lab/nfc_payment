import webbrowser

import nfc
from nfc.tag import Tag

# https://github.com/nfcpy/nfcpy
# https://nfcpy.readthedocs.io/en/latest/


# NFCに書き込まれている情報から必要な部分を抜き出す。
# NFCには`https://<サイト名>?id=1001`という情報が書き込まれている。
# 末尾のID部分4桁がほしい。
def get_id(tag: Tag) -> str:
    return tag.ndef.records[0].uri[-4:]


# IDからURLを再構成してそのURLをウェブブラウザで開く。
def send_url(client_id) -> None:
    url = "<各自のサーバーURL>"
    url = url + "?id=" + client_id
    print(url)
    webbrowser.open(url, new=0, autoraise=True)


# カードを読み込んだ時に行う処理
def on_connect(tag: Tag) -> bool:
    send_url(get_id(tag))
    return True


# 無限ループして待ち状態にする。
if __name__ == "__main__":
    print("=====history====")
    while True:
        with nfc.ContactlessFrontend("usb") as clf:
            clf.connect(rdwr={"on-connect": on_connect})
