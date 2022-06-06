const main = () => {
  const $imageInput = document.getElementById('images');
  const $previewContainer = document.getElementById('js-preview-container');
  const $form = document.getElementById('post');
  const $thumbnailImageIndexField = document.getElementById('thumbnail-image-index');

  if ($imageInput === null || $previewContainer === null || $form === null) {
    throw new Error();
  }

  /** @type HTMLImageElement[] */
  const previewImages = [];

  // クリックされた画像を thumbnail_image_index フィールドにセットし、スタイルを適用
  const onClickImage = ({ target }) => {
    if (target instanceof HTMLImageElement) {
      previewImages.forEach((image, index) => {
        if (image.isEqualNode(target)) {
          if ($thumbnailImageIndexField instanceof HTMLInputElement) {
            $thumbnailImageIndexField.value = index;
          }

          image.classList.add('border-teal-600');
        } else if (image.classList.contains('border-teal-600')) {
          image.classList.remove('border-teal-600');
        }
      });
    }
  };

  $imageInput.addEventListener('change', ({ target }) => {
    if (target instanceof HTMLInputElement) {
      const files = Array.from(target.files);

      if (previewImages.length === 0) {
        if ($thumbnailImageIndexField instanceof HTMLInputElement) {
          $thumbnailImageIndexField.value = index;
        }
      }

      files.forEach((file, index) => {
        const $preview = document.createElement('img');
        $preview.setAttribute('src', window.URL.createObjectURL(file));
        $preview.setAttribute(
          'class',
          `w-20 cursor-pointer hover:opacity-80 border-2 border-transparent${index === 0 ? ' border-teal-600' : ''}`,
        );
        $preview.setAttribute('alt', '添付画像のプレビュー');
        $preview.addEventListener('click', onClickImage);

        $previewContainer.appendChild($preview);
        previewImages.push($preview);
      });
    }
  });

  $form instanceof HTMLFormElement && $form.addEventListener('submit', (e) => {});
};

main();
