/**
 * @description preview chosen image
 * @requires input#image, div#js-preview-container
 * TODO: 一度プレビューを表示した後画像を変更すると壊れる
 */
const previewImageWhenImageChosen = () => {
  const $imageInput = document.getElementById('image');
  const $previewContainer = document.getElementById('js-preview-container');
  const $preview = document.getElementById('js-preview');

  if ($imageInput === null || $previewContainer === null) {
    throw new Error();
  }

  $imageInput.addEventListener('change', (e) => {
    if (e.target instanceof HTMLInputElement) {
      const url = window.URL.createObjectURL(e.target.files[0]);

      const $image = document.createElement('img');
      $image.src = url;
      $image.id = 'js-preview';
      $image.alt = 'プロフィール画像のプレビュー';
      $image.width = 200;

      $previewContainer.appendChild($image);
    }
  });
};

previewImageWhenImageChosen();
