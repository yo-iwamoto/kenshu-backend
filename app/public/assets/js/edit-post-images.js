const imagesFieldHtml =
  '<input id="images" aria-describedby="post images" type="file" accept="image/*" name="images[]" multiple>';

const imagesDisplay = document.getElementById('js-images-display');
const imagesForm = document.getElementById('js-images-form');
const editButton = document.getElementById('js-images-edit');
const cancelEditButton = document.getElementById('js-images-cancel-edit');
const imagesContainer = document.getElementById('js-images-container');
const previewContainer = document.getElementById('js-preview-container');
const form = document.getElementById('post');
const thumbnailImageIndexField = document.getElementById('thumbnail_image_index');
let imagesField = document.getElementById('images');

if (
  imagesDisplay === null ||
  imagesForm === null ||
  editButton === null ||
  !(imagesField instanceof HTMLInputElement) ||
  imagesField === null ||
  previewContainer === null ||
  form === null ||
  thumbnailImageIndexField === null
) {
  throw new Error();
}

/** @type HTMLImageElement[] */
const previewImages = [];

// クリックされた画像を thumbnail_image_index フィールドにセットし、スタイルを適用
const onClickImage = ({ target }) => {
  if (target instanceof HTMLImageElement) {
    previewImages.forEach((image, index) => {
      if (image.isEqualNode(target)) {
        if (thumbnailImageIndexField instanceof HTMLInputElement) {
          thumbnailImageIndexField.value = index;
        }

        image.classList.add('border-teal-600');
      } else if (image.classList.contains('border-teal-600')) {
        image.classList.remove('border-teal-600');
      }
    });
  }
};

const registerListener = () => {
  imagesField.addEventListener('change', ({ target }) => {
    if (target instanceof HTMLInputElement) {
      const files = Array.from(target.files);

      if (previewImages.length === 0) {
        if (thumbnailImageIndexField instanceof HTMLInputElement) {
          thumbnailImageIndexField.removeAttribute('value');
        }
      }

      files.forEach((file, index) => {
        const preview = document.createElement('img');
        preview.setAttribute('src', window.URL.createObjectURL(file));
        preview.setAttribute(
          'class',
          `w-20 cursor-pointer hover:opacity-80 border-2 border-transparent${index === 0 ? ' border-teal-600' : ''}`,
        );
        preview.setAttribute('alt', '添付画像のプレビュー');
        preview.addEventListener('click', onClickImage);

        previewContainer.appendChild(preview);
        previewImages.push(preview);
      });
    }
  });
};

const resetForm = () => {
  imagesField.remove();
  imagesContainer.innerHTML += imagesFieldHtml;
  imagesField = imagesContainer.children[imagesContainer.children.length - 1];
  registerListener();

  thumbnailImageIndexField.value = '';

  document.getElementById('js-preview-container').innerHTML = '';
  previewImages.splice(0);
};

const showDisplay = () => {
  imagesDisplay.hidden = false;
  imagesForm.hidden = true;

  resetForm();
};
const showForm = () => {
  imagesDisplay.hidden = true;
  imagesForm.hidden = false;
};

editButton.addEventListener('click', showForm);
cancelEditButton.addEventListener('click', showDisplay);

registerListener();
