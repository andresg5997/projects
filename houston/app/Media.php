<?php

namespace App;

use Conner\Likeable\LikeableTrait;
use Conner\Tagging\Taggable;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Share;
use Storage;

class Media extends Model
{
    use LikeableTrait;
    use Taggable;

    protected $fillable = [
        'slug', 'category_id', 'upload_session', 'views', 'title', 'type',
        'body', 'private', 'password', 'ip', 'anonymous', 'key', 'approved',
        'plays', 'last_viewed_at', 'cloned', 'user_id', 'disk', 'team_id',
        'post_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function post(){
        return $this->belongsTo('App\Post');
    }

    // Media User Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Media Category Relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Media Attachments Relationship
    public function attachments($type = null)
    {
        if ($type == 'gallery') {
            return $this->hasMany(Attachment::class);
        }

        return $this->hasOne(Attachment::class);
    }

    // Media Flags Relationship
    public function flags()
    {
        return $this->hasMany(Flag::class, 'flagged_id')->where('type', 'media');
    }

    // media flags count

    public function flagsCount()
    {
        return $this->flags()->count();
    }

    // Media Comments Relationship
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Order Media Comments BY Most Likes and from newest to oldest
    public function commentsMostLikes()
    {
        return $this->comments()->withCount('likes')->orderBy('likes_count', 'desc')->orderBy('id', 'desc');
    }

    public function previewImageUrl($quality = null)
    {
        if ($this->type == 'picture') {
            $image = $this->getAttachmentContent();

            if ($quality == 'original') {
                return $this->originalImageUrl($image);
            }

            return $this->thumbnailLargeUrl($image);
        } elseif ($this->type == 'video') {
            $content = $this->getAttachmentContent();

            return $this->thumbnailLargeUrl($content);
        } elseif ($this->type == 'gallery') {
            return url('assets/images/circled_play.png');
        } elseif ($this->type == 'audio') {
            return url('assets/images/sound.png');
        } elseif ($this->type == 'application' || $this->type == 'text') {
            $application = $this->getAttachmentContent();
            $extension = $application->file_mime;
            $path = getcwd();
            $local_path = 'assets/images/file-types/'.$extension.'_512.png';
            $check = $path.'/'.$local_path;

            $word = ['doc', 'dotx', 'docm', 'docx'];
            $powerpoint = ['ppt', 'pot', 'pps', 'pptx', 'pptm', 'potm',
                'potx', 'ppam', 'ppsx', 'ppsm', 'sldx', 'sldm', ];
            $excel = ['xls', 'xls', 'xlsx', 'xlsm', 'xltx', 'xltm'];
            $html = ['html', 'htm', 'xhtml'];
            $code = ['m', 'c', 'js', 'sass', 'scss'];

            if (in_array($extension, $word)) {
                return url('assets/images/file-types/word_512.png');
            } elseif (in_array($extension, $powerpoint)) {
                return url('assets/images/file-types/powerpoint_512.png');
            } elseif (in_array($extension, $excel)) {
                return url('assets/images/file-types/excel_512.png');
            } elseif (in_array($extension, $html)) {
                return url('assets/images/file-types/html_512.png');
            } elseif (in_array($extension, $code)) {
                return url('assets/images/file-types/code_512.png');
            } elseif (file_exists($check)) {
                return url($local_path);
            } else {
                return url('assets/images/application.png');
            }
        }
    }

    /*
     * Check if upload_session folder has multiple images
     * if it does, then return the file path but exclude the original file
     * because that one is already getting displayed
     * also return the thumbnail paths
     */
    public function isGallery()
    {
        $upload_session = $this->getUploadSession();
        $original_path = $this->previewImageUrl('original');
        $thumbnail_l_path = $this->previewImageUrl('thumbnail-l');

        // files is an array that includes all names of image files including the extension
        $files = glob("uploads/content/media/$upload_session/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

        // only do this if more than 3 files are there, because if one image is uploaded
        // then it creates 2 thumbnails

        // if 1 image and one video is uploaded it does not work because video create thumbnails as well

        if (count($files) > 3) {
            // $file_paths includes the file_paths without the thumbnails
            $file_paths[] = [];
            foreach ($files as $file) {
                // make sure it is not a thumbnail image and an image file
                $pos1 = strpos($file, 'thumbnail');
                $pos2 = strpbrk($file, implode(['png']));

                if ($pos1 === false && $pos2 !== false && $original_path != url($file)) {
                    $file_paths['file_paths'][] = $file;
                }
            }

            // in case a video is uploaded and an image there will be thumbnails generated for both
            // but it should only be treated like one image so only do it if there is more than 1
            if (count($file_paths) > 1) {
                $file_paths_thumb[] = [];
                foreach ($files as $file) {
                    $pos1 = strpos($file, 'thumbnail-l');
                    if ($pos1 !== false && $thumbnail_l_path != url($file)) {
                        // there is a change that it is a video thumbnail, so check if the actual media file is a picture
                        $file_paths_thumb['file_paths_thumb'][] = $file;
                    }
                }

                return array_merge($file_paths, $file_paths_thumb);
            }
        }
    }

    public function getUploadSession()
    {
        $media = $this->getAttachmentContent();

        $upload_session = $media->upload_session;

        return $upload_session;
    }

    public function downloadUrl()
    {
        $content = $this->getAttachmentContent();

        $disk = $this->disk;

        if ($disk == 'local' || $disk == 'dropbox' || $disk == '') {
            $path = url('/') . '/uploads/content/media/'.$content->upload_session.'/'.$content->file_name.'.';
            $file_mime = $this->streamExtension($this->type);

            return $path.$file_mime;
        } else {
            $file_mime = $this->streamExtension($this->type);
            if(\App::environment('production')){
                return Storage::disk('s3')->url($content->upload_session.'/'.$content->file_name.'.'.$file_mime);
            }else{
                return Storage::disk($disk)->url($content->upload_session.'/'.$content->file_name.'.'.$file_mime);
            }
        }
    }

    public function streamExtension($type)
    {
        $content = $this->getAttachmentContent();

        if ($type == 'video' || $this->type == 'video') {
            $stream_extension = 'mp4';
        } else {
            $stream_extension = $content->file_mime;
        }

        return $stream_extension;
    }

    public function humanFileSize($dec = 2)
    {
        $content = $this->getAttachmentContent();

        $file_size = $content->file_size;
        if ($file_size == 0) {
            return '0.00 B';
        } else {
            $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            $factor = floor((strlen($file_size) - 1) / 3);

            return sprintf("%.{$dec}f", $file_size / pow(1024, $factor)).@$size[$factor];
        }
    }

    public function maxHeightOrWidth($image)
    {
        list($width, $height) = getimagesize($image);

        if ($height >= $width) {
            return 'width: 100px; position: relative; left: 30px;';
        } else {
            return '';
        }
    }

    public function socialLinks()
    {
        $content = $this->getAttachmentContent();
        $file_name = $content->file_name;
        $file_original_name = $content->file_original_name;
        $social_links = Share::load("https://clooud.tv/media/$file_name", $file_original_name)->services('facebook', 'gplus', 'twitter', 'tumblr', 'pinterest', 'delicious', 'digg', 'reddit', 'email');

        return $social_links;
    }

    public function isGif($type = null)
    {
        if ($type == 'gallery') {
            $image = $this->attachments->content;

            return ($image['file_mime'] == 'gif') ? true : false;
        } else {
            $image = $this->getAttachmentContent();

            return ($image->file_mime == 'gif') ? true : false;
        }
    }

    // Order Media By Most Popular
    public static function orderByMostPopular()
    {
        return self::orderBy('views', 'desc');
    }

    // Order Media By Most Likes
    public static function orderByMostLikes()
    {
        return self::withCount('likes')->orderBy('likes_count', 'desc');
    }

    // Order Media By Most Likes
    public static function orderByMostComments()
    {
        return self::withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function userOtherMedia($user_id, $current_media_id)
    {
        return $this->where('user_id', $user_id)
            ->where('id', '!=', $current_media_id)
            ->where('private', 0)
            ->where('anonymous', 0)
            ->where('approved', 1)
            ->latest()
            ->take(4)
            ->get();
    }

    public function readableCount($type)
    {
        if ($type == 'likes') {
            $count = ($this->count == null) ? $this->likeCount : $this->count;
        } elseif ($type == 'views') {
            $count = $this->views;
        } else {
            $count = $this->comments->count();
        }

        if ($count >= 1000) {
            return round($count / 1000, 1).'k';
        } else {
            return $count;
        }
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function getAttachmentContent()
    {
        if (! $this->cloned) {
            return json_decode($this->attachments->content);
        } else {
            if (filter_var($this->cloned, FILTER_VALIDATE_URL)) {
                // Openload links are stored in the cloned column
                return json_decode($this->attachments->content);
            } else {
                return json_decode((Attachment::where('media_id', $this->cloned)->first())->content);
            }
        }
    }

    public function thumbnailLargeUrl($image)
    {
        $upload_session = $image->upload_session;
        $file_name = $image->file_name;
        $extension = $image->file_mime;

        $disk = $this->disk;

        if ($disk == 'local' || $disk == 'dropbox' || $disk == '') {
            if ($this->type == 'picture') {
                // image thumbnails keep the original file extension while others get converted to jpg
                $thumbnail_l_path = 'uploads/content/media/'.$upload_session.'/'.$file_name.'-thumbnail-l.'.$extension;
            } else {
                $thumbnail_l_path = 'uploads/content/media/'.$upload_session.'/'.$file_name.'-thumbnail-l.jpg';
            }

            if (File::exists($thumbnail_l_path)) {
                return url($thumbnail_l_path);
            } else {
                return url('assets/images/filetype-na.png');
            }
        } else {
            $thumbnail_l_path = $upload_session.'/'.$file_name.'-thumbnail-l.jpg';

            if(\App::environment('production')){
                if (Storage::disk('s3')->exists($thumbnail_l_path)) {
                    return Storage::disk('s3')->url($thumbnail_l_path);
                } elseif (File::exists('uploads/content/media/'.$thumbnail_l_path)) {
                    return url('uploads/content/media/'.$thumbnail_l_path);
                } else {
                    return url('assets/images/filetype-na.png');
                }
            }else{
                if (Storage::disk($disk)->exists($thumbnail_l_path)) {
                    return Storage::disk($disk)->url($thumbnail_l_path);
                } elseif (File::exists('uploads/content/media/'.$thumbnail_l_path)) {
                    return url('uploads/content/media/'.$thumbnail_l_path);
                } else {
                    return url('assets/images/filetype-na.png');
                }
            }

        }
    }

    public function originalImageUrl($image)
    {
        $upload_session = $image->upload_session;
        $file_name = $image->file_name;
        $extension = $image->file_mime;

        $disk = $this->disk;

        if ($disk == 'local' || $disk == 'dropbox' || $disk == '') {
            return url('uploads/content/media/'.$upload_session.'/'.$file_name.'.'.$extension);
        } else {
            if(\App::environment('production')){
                return Storage::disk('s3')->url($upload_session.'/'.$file_name.'.'.$extension);
            }else{
                return Storage::disk($disk)->url($upload_session.'/'.$file_name.'.'.$extension);
            }
        }
    }

    public function posts(){
        return $this->hasMany('App\Post');
    }
}
