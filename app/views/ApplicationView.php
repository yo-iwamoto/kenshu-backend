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
    <main class="font-mono">
        <?php echo $content; ?>
    </main>
</body>

</html>

<?php
    }
}
