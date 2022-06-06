const toggle = () => {
  const $toggleButton = document.getElementById('js-toggle-button');
  const $toggleBody = document.getElementById('js-toggle-body');

  if ($toggleButton === null || $toggleBody === null) {
    return;
  }

  let isOpen = true;

  $toggleButton.addEventListener('click', () => {
    if (isOpen) {
      $toggleBody.classList.add('h-0');
      $toggleButton.classList.add('rotate-180');
      isOpen = false;
    } else {
      $toggleBody.classList.remove('h-0');
      $toggleButton.classList.remove('rotate-180');
      isOpen = true;
    }
  });
};

toggle();
