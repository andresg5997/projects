import Vue from 'vue'
import axios from 'axios'
import jquery from 'jquery'
import autoLink from 'autolink.js'
import VueMask from 'v-mask'

// Vue.directive('mask', VueMaskDirective);
Vue.use(VueMask)
// import VueGoogleAutocomplete from 'vue-google-autocomplete'

window.dashboard = new Vue({
    el: '#dashboard',
    data() {
        return {
            invite: {
                email: '',
                name: ''
            },
            editTitle: storage.editTitle,
            events: [],
            season: '',
            createTitle: storage.createTitle,
            viewTitle: storage.viewTitle,
            postModalTitle: storage.postModalTitle,
            edit: false,
            user_id: storage.user_id,
            postFields: {
                user_id: storage.user_id,
                team_id: storage.team_id,
                content: '',
                subject: '',
                id: 0,
                files: 0,
                medias: null
            },
            commentFields: {
                user_id: storage.user_id,
                post_id: '',
                body: '',
                id: ''
            },
            posts: [],
            deleteMediaLink: '/deleteMedia',
            viewPost: {
                content: ''
            },
            coach: {
                name: '',
                title: '',
                phone: '',
                email: ''
            },
            editCoach: {
                name: '',
                title: '',
                phone: '',
                email: ''
            },
            errors: {},
            team: storage.team,
            mediaPosts: []
        }
    },
    methods: {
        getMediaPosts(){
            axios.get(`/teams/${storage.team_id}/getMedia`)
            .then(function (res) {
                    dashboard.mediaPosts = res.data.posts
            })
            .catch(function(error){
                console.log(error);
            })
        },
        deleteArchive(id){
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true
            })
                .then(() => {
                    axios.delete(`/archives/${id}`)
                        .then((res) => {
                            if(res.data.deleted) {
                                swal('Success!', 'The archive was successfully deleted!', 'success')
                                setTimeout(location.reload.bind(location), 2500)
                            }else{
                                swal('Error!', 'You don\'t have the rights to delete this archive.', 'error')
                            }
                        })
                })
        },
        openEditCoach(id){
            this.editCoach = this.team.coaches.filter((coach) => coach.id == id)[0]
        },
        updateCoach(){
            axios.put(`/teams/${storage.team_id}/coach/${this.editCoach.id}`, this.editCoach)
                .then((res) => {
                    if(res.data.updated) {
                        this.closeModal()
                        swal('Success!', 'The coach was successfully updated!', 'success')
                        setTimeout(location.reload.bind(location), 2500)
                    }
                })
        },
        questionRemoveCoach(id){
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true
            })
                .then(() => {
                    axios.delete(`/teams/${storage.team_id}/coach/${id}`)
                        .then((res) => {
                            if(res.data.deleted) {
                                swal('Success!', 'The coach was successfully deleted!', 'success')
                                setTimeout(location.reload.bind(location), 2500)
                            }
                        })
                })
        },
        submitCoach(){
            this.errors = {}
            axios.post(`/teams/${storage.team_id}/coach`, this.coach)
                .then((res) => {
                    if(res.data.saved){
                        this.closeModal()
                        swal('Success!', 'New coach was successfully added!', 'success')
                        setTimeout(location.reload.bind(location), 2500)
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data
                })
        },
        updateViewPost(post){
            this.viewPost = post;
            this.postModalTitle = this.viewTitle
        },
        deleteMedia(slug, index){
            axios.delete(this.deleteMediaLink+'/'+slug)
            .then((res) => {
                swal('Media has been deleted!', '', 'success');
                dashboard.updatePosts();
                this.postFields.medias.splice(index, 1);
            })
            .catch((error) => console.log(error));
        },
        closeModal(){
            $('#postModal').modal('hide');
            $('#viewPostModal').modal('hide');
            $('#coachModal').modal('hide');
            $('.modal-backdrop.in').remove();
            this.edit = !this.edit;
            // Reiniciamos el modal a su estado original
            this.postModalTitle = this.createTitle;
            this.postFields.content = '';
            this.postFields.subject = '';
            this.postFields.id = 0;
            this.postFields.files = 0;
            this.postFields.medias = null;
            this.viewPost = {
                content: ''
            };
        },
        updatePost(){
            dashboard.postModalTitle = dashboard.createTitle;
            axios.put('/updatePost', this.postFields)
                .then(() => {
                    dashboard.updatePosts();
                    swal('Your post was updated!', '', 'success');
                    edit: false;
                    this.postFields.content = '';
                    this.postFields.subject = '';
                    this.postFields.id = '';
                    this.postFields.files = 0;
                    this.postFields.medias = null;
                })
                    .catch((error) => {
                        console.error(error);
                        swal('Error!', '', 'error');
                    })

            $('#postModal').modal('hide');
            $('.modal-backdrop.in').remove();
        },
        filesChanged(e){
            // console.log(e.target.files.length);
            this.postFields.files = e.target.files.length;
        },
        submitMessage() {
            if(this.postFields.content != '' || this.postFields.subject != ''){
                if(this.postFields.files > 0){
                    $('#newPost').submit();
                }else{
                    axios.post("/post/store", this.postFields)
                        .then(function (res) {
                            swal({
                                title: 'Posted!',
                                text: 'Your message was successfully posted!',
                                type: "success",
                                showCancelButton: false,
                                confirmButtonText: 'Ok'
                            }).then(function(){
                                $('#postModal').modal('hide');
                                $('.modal-backdrop.in').remove();
                            });
                            dashboard.postFields.content = '';
                            dashboard.postFields.subject = '';
                            dashboard.updatePosts();
                        })
                        .catch(function (error) {
                            console.log(error);
                            swal({
                                title: "Error",
                                text: "An error ocurred!",
                                type: "error",
                                showCancelButton: false,
                                confirmButtonText: "Ok"
                            });
                        });

                    return;
                }
            }else{
                swal('Error', 'Remember to fill both subject and content fields of your post.', 'error');
            }
        },
        updatePosts(){
            axios.get(`/postsTeam/${storage.team_id}`)
            .then(function (response) {
                // dashboard.posts = response.data;
                dashboard.posts = response.data.map((post) => {
                    post.parsedContent = autoLink(post.content)
                    return post
                })
            })
            .catch(function(error){
                console.log(error);
            })
        },
        editPost(post){
            this.postModalTitle = this.editTitle;
            this.edit = true;
            this.postFields.id = post.id;
            this.postFields.content = post.content;
            this.postFields.subject = post.subject;
            this.postFields.medias = post.medias;
            $('#postModal').modal('show');
        },
        deletePost(id){
            dashboard.postFields.id = id;
            swal({
            title: "Are you sure?",
            text: "This can't be undone!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            })
            .then(function(){
                axios({
                    url: '/deletePost',
                    data: {
                        id: id
                    },
                    method: 'delete'
                })
                .then(function(){
                    dashboard.closeModal();
                    dashboard.updatePosts();
                    swal('Your post was deleted', '', 'success');
                })
                .catch(function(error){
                    swal("Error", '', 'error');
                    // console.log(error);
                });
            })
            this.postFields = {};
        },
        fullName(elem){
            return elem.first_name + ' ' + elem.last_name;
        },
        comment(id){
            var postId = id;
            swal({
                title: 'Comment',
                input: 'textarea',
                confirmButtonColor: '#004080',
                confirmButtonText: 'Comment',
                showCancelButton: true,
                inputValidator (value) {
                    return new Promise(function (resolve, reject) {
                      if (value) {
                        resolve()
                      } else {
                        reject("You need to write something!")
                      }
                    })
                  }
            }).then(function (text){
                // If confirmed
                dashboard.commentFields.post_id = postId;
                dashboard.commentFields.body = text;
                axios.put("/putComment", dashboard.commentFields)
                .then(function(res){
                    dashboard.updatePosts();
                    swal({
                        title: 'Comment posted!',
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                        timer: 4000
                    });
                })
                .catch(function(error){
                    console.log(error);
                });
            });
            this.commentFields = {};
        },
        editComment(id, comment){
            var commentId = id;
            swal({
                title: 'Edit comment',
                input: 'textarea',
                confirmButtonColor: '#004080',
                confirmButtonText: 'Update',
                showCancelButton: true,
                inputValue: comment,
                inputValidator (value) {
                    return new Promise(function (resolve, reject) {
                      if (value) {
                        resolve()
                      } else {
                        reject("You need to write something!")
                      }
                    })
                  }
            }).then(function(text){
                dashboard.commentFields.id = commentId;
                dashboard.commentFields.body = text;
                axios.put("/updateComment", dashboard.commentFields)
                .then(function(res){
                    dashboard.updatePosts();
                    swal("Edit comment", 'Your comment was updated!' , 'success');
                }).catch(function(error){
                    swal("Error", '', 'error');
                    console.log(error);
                });
            })
            this.commentFields = {};
        },
        deleteComment(id){
            dashboard.commentFields.id = id;
            swal({
            title: "Are you sure?",
            text: "This can't be undone!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            })
            .then(function(){
                axios({
                    url: '/deleteComment',
                    data: {
                        id: id
                    },
                    method: 'delete'
                })
                .then(function(){
                    swal('Your comment was deleted!', '', 'success');
                })
                .catch(function(error){
                    swal("Error", '', 'error');
                    console.log(error);
                });
                dashboard.updatePosts();
            })
            this.commentFields = {};
        },
        eStatus(date){
            var eventDate = new Date(date).getTime()
            var currentDate = Date.now()
            if(currentDate >= eventDate){
                return 'update'
            }
            return 'upcoming'
        },
        getEvents(){
            axios.get(`/teams/${this.postFields.team_id}/dashboardEvents`)
                .then((res) => {
                    this.events = res.data.events
                    this.events.map((event) => {
                        if(event.goals_1){
                            event.status = 'loaded'
                        }else{
                            event.status = this.eStatus(event.date)
                        }
                    })
                    this.season = res.data.season
                    setTimeout(() => {
                        $('.editable').editable({})
                    }, 1000)
                })
                .catch((error) => console.log(error))
        },
        sendInvitationForm(){
            axios.post('/teams/' + parseInt(storage.team_id) + '/invite', {email: this.invite.email, name: this.invite.name})
                .then((res) => {
                    $('#inviteModal').modal('hide')
                    if(res.data.sent){
                        return swal({
                          type: 'success',
                          title: 'Invitation sent!'
                        })
                    }
                    return swal({
                        type: 'warning',
                        title: 'That email is already registered!'
                    })
                })
        }
    },
    filters: {
        moment(date){
            return moment(date).format('DD-MMM-YYYY [at] H:mm')
        },
        eMoment(date){
            return moment(date).format('ddd DD MMM')
        }
    },
    computed: {
    },
    created(){
        this.updatePosts()
        this.getEvents()
        this.getMediaPosts()
    }
})
