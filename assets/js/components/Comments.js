import React, {Component, Fragment} from 'react';
import axios from 'axios';
import Arrow from '../../images/grayarrow.gif';
import timeago from 'epoch-timeago';

class Comments extends Component {
    constructor() {
        super();
        this.state = { comments: [], loading: true};
    }
    
    componentDidMount() {
        this.getAllComments();
    }
    
    getAllComments() {
        axios.get(`http://localhost:8000/newcomments`).then(res => {
            const comments = res.data.kids;
            this.setState({ comments, loading: false });
        })
    }
    
    render() {
        const loading = this.state.loading;
        const TimeAgo = ({ time }) =>
        <time dateTime={new Date(time).toISOString()}>{timeago(time)}</time>;

        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        {loading ? (
                            <div className={'row text-center'}>
                                <span className="fa fa-spin fa-spinner fa-4x"></span>
                            </div>
                        ) : (
                            <div className={'row'}>
                                {this.state.comments.map ((comment, i) =>
                                    
                                    <div className="col-md-12 row-block" key={i++}>
                                        <div className="media">
                                            <div className="media-body">
                                                <p>
                                                    <a href={'/vote/' + comment.kids.JSON.parse (id) + '&how=up&goto=news'}><img className="arrow" src={Arrow} alt="Arrow" /></a>
                                                    <span className="grey-small">
                                                        <a href={'/user/' + comment.kids.JSON.parse (by)} className="link">{comment.kids.JSON.parse (by)}</a>&nbsp;
                                                        <a href={'/item/' + comment.kids.JSON.parse (id)} className="link"><TimeAgo time={comment.kids.JSON.parse (time) * 1000} /></a> |&nbsp;
                                                        <a href={'/item/' + JSON.parse (comment.kids.parent) + '&goto=news'} className="link">parent</a> |
                                                        on: {<a href={'/item/' + comment.kids.JSON.parse (id)} className="link">{JSON.parse (comment)}</a>}
                                                    </span>
                                                </p>
                                                <p>
                                                    {comment.kids.JSON.parse (text)}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                )}
                                <Fragment>
                                    <div className="col-md-12 row-block">
                                        <a href="/" className="more-link link">More</a>
                                    </div>
                                </Fragment>
                            </div>
                        )}
                    </div>
                </section>
            </div>
        )
    }
}
export default Comments;