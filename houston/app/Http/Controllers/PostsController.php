<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Media;
use App\Comment;
use App\Attachment;
use Conner\Likeable\Like;
use Illuminate\Http\Request;
use Conner\Likeable\LikeCounter;
use Validator;
use App\Http\Controllers\Team\MediaController;

class PostsController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'content'           => 'required|min:4',
            'subject'           => 'required|min:4'
        ]);

        if($validator->fails()){
            flash(trans('team.error_msg'), 'error');
            return redirect()->route('teams.show', $request->team_id);
        }

        $post = new Post($request->except(''));
        $post->user_id = \Auth::id();
        $post->save();


        // Unimos el post con el request para subir las imágenes relacionadas
        // y se les ponga el id
        $request->merge(['post_id' => $post->id]);

        $m = new MediaController;
        $medias = $m->directUploadHandler($request);

        flash(trans('team.post_success'), 'success')->important();

        return redirect()->route('teams.show', $request->team_id);
    }

    public function storePost(Request $request){
        $this->validate($request, [
            'content' => 'required|min:4',
            'subject' => 'required|min:4'
        ]);

        $post = new Post($request->except(''));
        $post->user_id = \Auth::id();
        $post->save();

        return response('Completed', 200);
    }

       public function putLike(Request $request)
    {
        $post = Post::findOrFail($request->input('id'));

        if ($post->liked()) {
            $post->unlike();
        } else {
            $post->like();
        }

        // ( $media->liked() ? $media->unlike() : $media->like());
        return response()->json($post->likeCount);
    }

    public static function dashboard($id){
        $posts = Post::where('team_id', $id)->with('media', 'user', 'comments', 'comments.user')->latest()->get();

        // Como estamos mostrando los mensajes con Vue, tenemos que utilizar
        // la función getAvatarUrl creada por Clooud para conseguir el avatar
        // del usuario y adherirsela al post.
        $posts->map(function($post){
            $post->avatar_url = getAvatarUrl($post->user->id);
            $post->profile_url = route('user.profile.index', $post->user->username);

            if($post->likeCount > 0){
                $post->likesTotal = $post->likeCount;
            }else{
                $post->likesTotal = 0;
            }

            if(count($post->comments) > 0){
                $post->commentsTotal = count($post->comments);

                // Igual que arriba, conseguimos desde Laravel las url del avatar
                // y el perfil del usuario para utilizarlos en Vue.
                $post->comments->map(function($comment){
                    $comment->avatar_url = getAvatarUrl($comment->user->id);
                    $comment->profile_url = route('user.profile.index', $comment->user->username);
                });
            }else{
                $post->commentsTotal = 0;
            }

            if($post->liked()){
                $post->liked = true;
            }else{
                $post->liked = false;
            }

            // Verificamos si el post tiene media para añadirlos en
            // un formato que podemos recibir y mostrar con Vue.
            if($post->media){
                $post->medias = collect();
                $post->media_count = 0;

                foreach($post->media as $media){
                    $post->media_count++;
                    $post->medias->push([
                            'link'      => route('media.show', $media->slug),
                            'slug'      => $media->slug,
                            'url'       => $media->previewImageUrl('original')
                        ]);
                }
            }


            if(count($post->comments) > 0){
                $post->comments->map(function ($comment){
                    $comment->profile_url = route('user.profile.index', $comment->user->username);
                });
            }

            return $post;
        });
        return response()->json($posts);
    }

    public function putComment(Request $request){
        $comment = new Comment($request->except(''));
        $comment->user_id = \Auth::id();
        $comment->save();
        return response(200);
    }

    public function updateComment(Request $request){
        $comment = Comment::find($request->id);
        // dd($request->id);
        if($comment->user_id == \Auth::id()){
            $comment->body = $request->body;
            $comment->save();
            return response('Success', 200);
        }else{
            return response('Not allowed.', 403);
        }
    }

        public function deleteComment(Request $request){
        $comment = Comment::find($request->id);
        if($comment->user_id == \Auth::id()){
            $comment->delete();
            return response('Success', 200);
        }else{
            return response('Not allowed.', 403);
        }
    }

    public function updatePost(Request $request){
        $post = Post::find($request->id);

        if($post->user_id == \Auth::id()){
            $post->subject = $request->subject;
            $post->content = $request->content;
            $post->save();
            return response('Success', 200);
        }else{
            return response('Not allowed.', 403);
        }
    }

    public function deletePost(Request $request){
        $post = Post::find($request->id);
        if($post->user_id == \Auth::id()){
            $post->delete();
            $post->media()->delete();
            return response('Success', 200);
        }else{
            return response('Not allowed.', 403);
        }
    }

    public function deleteMedia(Request $request, $slug){
        $media = Media::where('slug', $slug)->firstOrFail();
        $user_id = $media->user_id;
        $media_id = $media->id;
        $upload_session = $media->upload_session;

        $attachment = Attachment::where('media_id', $media_id)->firstOrFail();

        // Delete Tags, Comments, Flags, Tags
        $user = User::where('id', $user_id)->firstOrFail();
        $type = User::where('id', \Auth::id())->first()->type;
        $owner = (\Auth::id() == $user_id) ? true : false;

        if ($owner || $type == 'admin') {
            Like::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            LikeCounter::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            $media->retag([]);
            $media->comments()->delete();
            $media->delete();
            $attachment->delete();

            // delete upload session and all files in it
            $mask = "$slug*.*";
            $directory = public_path('uploads/content/media/'.$upload_session);

            array_map('unlink', glob($directory.'/'.$mask));

            if (count(glob("$directory/*")) === 0) {
                rmdir($directory);
            }

            flash('Media has been deleted!', 'success');

            return response('', 200);
        } else {
            return abort(403);
        }
    }
}
