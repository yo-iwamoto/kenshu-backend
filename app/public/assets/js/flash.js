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

  $closeFlashButton.addEventListener('click', () => {
    $flash.remove();
  });
};

controlFlash();
