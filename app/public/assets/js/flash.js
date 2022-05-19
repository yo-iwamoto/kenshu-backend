/**
 * flash の制御
 * @requires #js-flash, button#js-close-flash
 */
const controlFlash = () => {
  const $flash = document.getElementById('js-flash');
  const $closeFlashButton = document.getElementById('js-close-flash');

  if ($flash === null || $closeFlashButton === null) {
    throw new Error();
  }

  // 手動で消す場合
  $closeFlashButton.addEventListener('click', () => $flash.remove());

  // 操作しない場合5秒で消える
  setTimeout(() => $flash.remove());
};

controlFlash();
