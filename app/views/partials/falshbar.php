<?php

namespace App\views\partials;

use App\lib\Flash;

class FlashBar
{
    public function __construct(private ?Flash $flash)
    {
    }

    public function render()
    {
        if ($this->flash !== null) {
            ?>
<div id="js-flash"
    class="absolute right-8 top-24 p-1.5 opacity-90 shadow-lg rounded-lg <?= $this->flash->type === 'error' ? 'bg-red-600' : 'bg-teal-600' ?>">
    <p class="text-white flex items-center">
        <?php if ($this->flash->type === 'error') { ?>

        <svg xmlns="http://www.w3.org/2000/svg" class="fill-white h-8 w-8 mr-1">
            <path style="transform: scale(0.63)"
                d="M24.05 24.45ZM2 42 24 4 46 42ZM22.7 30.6H25.7V19.4H22.7ZM24.2 36.15Q24.85 36.15 25.275 35.725Q25.7 35.3 25.7 34.65Q25.7 34 25.275 33.575Q24.85 33.15 24.2 33.15Q23.55 33.15 23.125 33.575Q22.7 34 22.7 34.65Q22.7 35.3 23.125 35.725Q23.55 36.15 24.2 36.15ZM7.2 39H40.8L24 10Z" />
        </svg>

        <?php } else { ?>

        <svg xmlns="http://www.w3.org/2000/svg" class="fill-white h-8 w-8 mr-1">
            <path style="transform: scale(0.63)"
                d="M22.65 34H25.65V22H22.65ZM24 18.3Q24.7 18.3 25.175 17.85Q25.65 17.4 25.65 16.7Q25.65 16 25.175 15.5Q24.7 15 24 15Q23.3 15 22.825 15.5Q22.35 16 22.35 16.7Q22.35 17.4 22.825 17.85Q23.3 18.3 24 18.3ZM24 44Q19.75 44 16.1 42.475Q12.45 40.95 9.75 38.25Q7.05 35.55 5.525 31.9Q4 28.25 4 24Q4 19.8 5.525 16.15Q7.05 12.5 9.75 9.8Q12.45 7.1 16.1 5.55Q19.75 4 24 4Q28.2 4 31.85 5.55Q35.5 7.1 38.2 9.8Q40.9 12.5 42.45 16.15Q44 19.8 44 24Q44 28.25 42.45 31.9Q40.9 35.55 38.2 38.25Q35.5 40.95 31.85 42.475Q28.2 44 24 44ZM24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24Q24 24 24 24ZM24 41Q31 41 36 36Q41 31 41 24Q41 17 36 12Q31 7 24 7Q17 7 12 12Q7 17 7 24Q7 31 12 36Q17 41 24 41Z" />
        </svg>

        <?php } ?>
        <span class="mr-3"><?= $this->flash->message ?></span>

        <button id="js-close-flash" class="pt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="fill-white h-6 w-6 mr-1">
                <path style="transform: scale(0.4)"
                    d="M12.45 37.65 10.35 35.55 21.9 24 10.35 12.45 12.45 10.35 24 21.9 35.55 10.35 37.65 12.45 26.1 24 37.65 35.55 35.55 37.65 24 26.1Z" />
            </svg>
        </button>
    </p>
</div>
<script src="/assets/js/flash.js"></script>
<?php
        }
    }
}
