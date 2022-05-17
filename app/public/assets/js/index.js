/**
 * @description preview chosen image
 * @requires input#image, div#preview-container
 */
const previewImageWhenImageChosen = () => {
  const $imageInput = document.getElementById('image');
  const $previewContainer = document.getElementById('preview-container');

  if ($imageInput === null || $previewContainer === null) {
    throw new Error('invalid DOM');
  }

  $imageInput.addEventListener('change', (e) => {
    console.log(e.target);
    if (e.target instanceof HTMLInputElement) {
      const url = window.URL.createObjectURL(e.target.files[0]);

      const $image = document.createElement('img');
      $image.src = url;
      $image.alt = 'プロフィール画像のプレビュー';
      $image.width = 200;

      $previewContainer.appendChild($image);
    }
  });
};

previewImageWhenImageChosen();
