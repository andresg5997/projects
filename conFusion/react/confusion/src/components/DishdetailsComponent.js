import React, { Component } from 'react';
import { Card, CardImg, CardText, CardTitle, CardBody, Breadcrumb, BreadcrumbItem, Button, Row, Modal, ModalHeader, ModalBody, Label } from 'reactstrap';
import { Control, LocalForm, Errors } from 'react-redux-form';
import { Link } from 'react-router-dom';
import { Loading } from './LoadingComponent';
import { baseUrl } from '../shared/baseUrl';
import { FadeTransform, Fade, Stagger } from 'react-animation-components';

function RenderDish({dish}) {
  return(
    <FadeTransform in transformProps={{exitTransform: 'scale(0.5) translateY(-50%)'}}>
      <Card>
        <CardImg top src={baseUrl + dish.image} alt={dish.name} />
        <CardBody>
          <CardTitle>{dish.name}</CardTitle>
          <CardText>{dish.description}</CardText>
        </CardBody>
      </Card>
    </FadeTransform>
  )
}

function RenderComments({comments}) {
  if (comments != null) {
    return(
      <div>
        <h4>Comments</h4>
        <ul className="list-unstyled">
          <Stagger in>
            { comments.map((comment) => {
              return(
                <Fade in>
                  <li key={comment.id}>
                    <p>{ comment.comment }</p>
                    <p>-- { comment.author }, {new Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'short', day: '2-digit'}).format(new Date(Date.parse(comment.date)))}</p>
                  </li>
                </Fade>
              );
            })}
          </Stagger>
        </ul>
      </div>
    );
  }
  else
    return(
      <div>
        <h4>Comments</h4>
        <p>No comments</p>
      </div>
    );
}

class CommentForm extends Component {

  constructor(props){
    super(props)

    this.state = {
      isCommentModalOpen: false
    };
    this.toggleCommentModal = this.toggleCommentModal.bind(this)
    this.handleCommentSubmit = this.handleCommentSubmit.bind(this)
  }

  toggleCommentModal(){
    this.setState({
      isCommentModalOpen: !this.state.isCommentModalOpen
    });
  }

  handleCommentSubmit(values){
    this.props.postComment(this.props.dishId, values.rating, values.name, values.message);
  }

  render(){
    const required = (val) => val && val.length;
    const maxLength = (len) => (val) => !(val) || (val.length <= len);
    const minLength = (len) => (val) => val && (val.length >= len);
    return(
      <React.Fragment>
        <Button outline onClick={this.toggleCommentModal}>
          <span className="fa fa-pencil fa-lg"></span> Submit Comment
        </Button>
        <Modal isOpen={this.state.isCommentModalOpen} toggle={this.toggleCommentModal}>
          <ModalHeader toggle={this.toggleCommentModal}>Submit Comment</ModalHeader>
          <ModalBody>
            <div className="container">
              <LocalForm onSubmit={(values) => this.handleCommentSubmit(values)}>
                <Row className="form-group">
                  <Label htmlFor="rating">Rating</Label>
                  <Control.select model=".rating" id="rating"
                  name="contactType" className="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </Control.select>
                </Row>
                <Row className="form-group">
                  <Label htmlFor="name">Your Name</Label>
                  <Control.text model=".name" id="name"
                  name="name" placeholder="Your Name" className="form-control"
                  validators={{required, minLength: minLength(3), maxLength: maxLength(15)}} />
                  <Errors
                    className="text-danger" model=".name" show="touched"
                    messages={{
                      required: 'Required ',
                      minLength: 'Must be greater than 2 characters ',
                      maxLength: 'Must be 15 characters or less '
                    }}
                  />
                </Row>
                <Row className="form-group">
                  <Label htmlFor="message">Your Feedback</Label>
                  <Control.textarea model=".message" id="message"
                  name="message" rows="6" className="form-control"/>
                </Row>
                <Row className="form-group">
                  <Button type="submit" color="primary">Submit</Button>
                </Row>
              </LocalForm>
            </div>
          </ModalBody>
        </Modal>
      </React.Fragment>
    )
  }
  
}

const Dishdetails = (props) => {
  if(props.isLoading) {
    return(
      <div className="container">
        <div className="row">
          <Loading />
        </div>
      </div>
    )
  }
  else if(props.errMess) {
    return(
      <h4>{props.errMess}</h4>
    )
  }
  else if (props.dish != null)
    return(
      <div className="container">
        <Breadcrumb className="mt-5">
          <BreadcrumbItem><Link to="/home">Home</Link></BreadcrumbItem>
          <BreadcrumbItem><Link to="/menu">Menu</Link></BreadcrumbItem>
          <BreadcrumbItem active>{props.dish.name}</BreadcrumbItem>
        </Breadcrumb>
        <div className="row">
          <div className="col-12 col-md-5 m-1">
            <RenderDish dish={props.dish}/>
          </div>
          <div className="col-12 col-md-5 m-1">
            <RenderComments comments={props.comments}/>
            <CommentForm postComment={props.postComment} dishId={props.dish.id}/>
          </div>
        </div>
      </div>
    );
  else
    return(
      <div></div>
    );
}

export default Dishdetails ;