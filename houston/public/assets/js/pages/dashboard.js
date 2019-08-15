var app = new Vue({
    el: '#app',
    data: {
        postFields: {
            user_id: '{{ Auth::id() }}',
            content: '',
            subject: '',
            team_id: '{{ $team->id }}',
            id: '',
            files: ''
        },
        commentFields: {
            user_id: '',
            post_id: '',
            body: '',
            id: ''
        },
        posts: []
    },
    mounted() {
        console.log(this.postFields);
    },
    methods: {
        submitMessage: function () {
            $('#newPost').submit();
            // axios.post(" route('posts.store') }}", this.postFields)
            //     .then(function (res) {
            //         swal({
            //             title: ' trans("team.post_confirm") }}',
            //             text: ' trans("team.post_success") }}',
            //             type: "success",
            //             showCancelButton: false,
            //             confirmButtonText: ' trans("team.confirm_button") }}'
            //         }).then(function(){
            //             $('#postModal').modal('hide');
            //             $('.modal-backdrop.in').remove();
            //         });
            //         app.postFields.content = '';
            //         app.postFields.subject = '';
            //         app.updatePosts();
            //     })
            //     .catch(function (error) {
            //         console.log(error);
            //         swal({
            //             title: " trans('team.error') }}",
            //             text: " trans('team.error_msg') }}",
            //             type: "error",
            //             showCancelButton: false,
            //             confirmButtonText: "Ok"
            //         });
            //     });
            
            // return;
        },
        updatePosts: function(){
            axios.get("{{ route('posts.team', $team->id) }}")
            .then(function (response) {
                app.posts = response.data;
            })
            .catch(function(error){
                console.log(error);
            })
        },
        editPost: function(id, postContent){
            var postId = id;
            swal({
                title: '{{ trans('team.post_edit') }}',                        
                input: 'textarea',
                confirmButtonColor: '#004080',
                confirmButtonText: '{{ trans('buttons.update') }}',
                showCancelButton: true,
                inputValue: postContent,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                      if (value) {
                        resolve()
                      } else {
                        reject("{{ trans('team.empty_field') }}")
                      }
                    })
                  }
            }).then(function(text){
                app.postFields.id = postId;
                app.postFields.content = text;
                axios.put("{{ route('posts.post.update') }}", app.postFields)
                .then(function(res){
                    app.updatePosts();
                    swal("{{ trans('team.post') }}", '{{ trans('team.post_updated') }}' , 'success');
                }).catch(function(error){
                    swal("{{ trans('team.error_msg') }}", '', 'error');
                    console.log(error);
                });
            })
            this.postFields = {};
        },
        deletePost: function(id){
            app.postFields.id = id;
            swal({
            title: "{{ trans('team.confirm_delete') }}",
            text: "{{ trans('team.delete_text') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ trans('team.confirm_delete') }}'
            })
            .then(function(){
                axios({
                    url: '{{ route('posts.post.delete') }}',
                    data: {
                        id: id
                    },
                    method: 'delete'
                })
                .then(function(){
                    app.updatePosts();
                    swal('{{ trans('team.post_deleted') }}', '', 'success');
                })
                .catch(function(error){
                    swal("{{ trans('team.error_msg') }}", '', 'error');
                    console.log(error);
                });
            })
            this.postFields = {};
        },
        fullName: function(elem){
            return elem.first_name + ' ' + elem.last_name;
        },
        comment: function(id){
            var postId = id;
            swal({
                title: '{{ trans('team.modal_post_title') }}',                        
                input: 'textarea',
                confirmButtonColor: '#004080',
                confirmButtonText: 'Comment',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                      if (value) {
                        resolve()
                      } else {
                        reject("{{ trans('team.empty_field') }}")
                      }
                    })
                  }
            }).then(function (text){
                // If confirmed
                app.commentFields.post_id = postId;
                app.commentFields.body = text;
                axios.put("{{ route('posts.comment') }}", app.commentFields)
                .then(function(res){
                    app.updatePosts();
                    swal({
                        title: '{{ trans("team.comment_confirm") }}',
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: '{{ trans("team.confirm_button") }}',
                        timer: 4000
                    });
                })
                .catch(function(error){
                    console.log(error);
                });
            });
            this.commentFields = {};
        },
        editComment: function(id, comment){
            var commentId = id;
            swal({
                title: '{{ trans('team.comment_edit') }}',                        
                input: 'textarea',
                confirmButtonColor: '#004080',
                confirmButtonText: '{{ trans('buttons.update') }}',
                showCancelButton: true,
                inputValue: comment,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                      if (value) {
                        resolve()
                      } else {
                        reject("{{ trans('team.empty_field') }}")
                      }
                    })
                  }
            }).then(function(text){
                app.commentFields.id = commentId;
                app.commentFields.body = text;
                axios.put("{{ route('posts.comment.update') }}", app.commentFields)
                .then(function(res){
                    app.updatePosts();
                    swal("{{ trans('team.comment_edit') }}", '{{ trans('team.comment_updated') }}' , 'success');
                }).catch(function(error){
                    swal("{{ trans('team.error_msg') }}", '', 'error');
                    console.log(error);
                });
            })
            this.commentFields = {};
        },
        deleteComment: function(id){
            app.commentFields.id = id;
            swal({
            title: "{{ trans('team.confirm_delete') }}",
            text: "{{ trans('team.delete_text') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ trans('team.confirm_delete') }}'
            })
            .then(function(){
                axios({
                    url: '{{ route('posts.comment.delete') }}',
                    data: {
                        id: id
                    },
                    method: 'delete'
                })
                .then(function(){
                    swal('{{ trans('team.comment_deleted') }}', '', 'success');
                })
                .catch(function(error){
                    swal("{{ trans('team.error_msg') }}", '', 'error');
                    console.log(error);
                });
                app.updatePosts();
            })
            this.commentFields = {};
        }
    },
    filters: {
        moment: function(date){
            return moment(date).format('DD-MMM-YYYY [at] H:mm');
        }
    },
    beforeMount: function(){
        this.updatePosts();
    }
})