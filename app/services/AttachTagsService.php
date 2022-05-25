<?php
namespace App\services;

use App\models\Post;
use App\models\PostToTag;

class AttachTagsService
{
    /**
     * 現在の状態に関わらず、tag_ids で指定されたタグに更新。
     *
     * 既に post_to_tags が存在し、tag_ids にも含まれるのものはそのまま。
     * 既に post_to_tags に存在し、tag_ids に含まれないものは削除。
     * post_to_tags に存在せず、tag_ids に含まれるのものは追加。
     */
    public static function execute(Post $post, array $tag_ids)
    {
        $current_tag_ids = [];

        if ($post->tags !== null) {
            foreach ($post->tags as $tag) {
                // 指定された tag_ids に含まれていいなければ、削除
                if (!in_array($tag->id, $tag_ids)) {
                    PostToTag::destroy($post->id, $tag->id);
                } else {
                    array_push($current_tag_ids, $tag->id);
                }
            }
        }

        // 指定された tag_ids のうち、現在存在していないものを追加
        foreach (array_diff($tag_ids, $current_tag_ids) as $tag_id) {
            PostToTag::create($post->id, $tag_id);
        }
    }
}
