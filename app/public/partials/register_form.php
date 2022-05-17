<form method="POST" action="/" class="flex flex-col gap-8" enctype="multipart/form-data">
    <div>
        <label for="name" class="font-bold">名前</label>
        <input id="name" type="text" name="name"
            class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
    </div>

    <label for="image">プロフィール画像</label>
    <input id="image" type="file" accept="image/*" src="" alt="" name="profile_image">

    <div id="preview-container"></div>

    <div>
        <label for="email" class="font-bold">メールアドレス</label>
        <input id="email" type="email" name="email"
            class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
    </div>

    <div>
        <label for="password" class="font-bold">パスワード</label>
        <input id="password" type="password" name="password"
            class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
    </div>

    <button type="submit"
        class="rounded-lg w-40 mx-auto text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 font-bold text-lg">登録</button>
</form>