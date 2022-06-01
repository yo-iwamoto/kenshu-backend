<?php
namespace App\views;

use App\views\partials;

class ApplicationView
{
    public static function render(string $content, array $data)
    {
        ?>

<!DOCTYPE html>

<?php partials\Head::render() ?>

<body>
    <?php partials\Header::render($data['is_authenticated'], csrf_token: $data['csrf_token']) ?>

    <?php if (isset($data['error_message'])) : ?>
    <div class="px-2">
        <div class="rounded-lg mx-auto max-w-3xl bg-red-200 px-6 py-3 mt-6 mb-2">
            <p class="text-red-800 text-sm">
                <?= $data['error_message'] ?>
            </p>
        </div>
    </div>
    <?php endif ?>

    <main class="font-mono">
        <?php echo $content; ?>
    </main>



</body>

</html>

<?php
    }
}
