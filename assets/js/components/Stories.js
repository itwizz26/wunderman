import React, {Component, Fragment} from 'react';
import axios from 'axios';
import Arrow from '../../images/grayarrow.gif';
import timeago from 'epoch-timeago';

class Stories extends Component {
    constructor() {
        super();
        this.state = { stories: [], loading: true};
    }
    
    componentDidMount() {
        this.getAllStories();
    }
    
    getAllStories() {
        axios.get(`http://localhost:8000/api/v1/all`).then(res => {
            const stories = res.data;
            this.setState({ stories, loading: false });
        })
    }
    
    render() {
        const loading = this.state.loading;
        const TimeAgo = ({ time }) =>
        <time dateTime={new Date(time).toISOString()}>{timeago(time)}</time>;

        function getDomain (url) {
            if (url)
            {
                var match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i);
                
                if (match != null && match.length > 2 && typeof match[2] === 'string' && match[2].length > 0) {
                    return match[2];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }

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
                                {this.state.stories.map ((story, i) =>
                                    
                                    <div className="col-md-12 row-block" key={i++}>
                                        <div className="media">
                                            <div className="media-body">
                                                <p>
                                                    <span className="number grey-small">{i++}. </span>
                                                    <a href={'/vote/' + JSON.parse (story).id + '&how=up&goto=news'}><img className="arrow" src={Arrow} alt="Arrow" /></a>
                                                    <a href={JSON.parse (story).url} className="black no-underline" target="_blank">{JSON.parse (story).title}</a>&nbsp;
                                                    
                                                    {(JSON.parse (story).url && JSON.parse (story).url.length) ? <span className="grey-small">(<a href="/site" className="link">{getDomain (JSON.parse (story).url)}</a>)</span>: ''}
                                                    
                                                </p>
                                                <p>
                                                    <span className="grey-small pad-left">
                                                        {JSON.parse (story).score} points by <a href={'/user/' + JSON.parse (story).by} className="link">{JSON.parse (story).by}</a>&nbsp;
                                                        <a href={'/item/' + JSON.parse (story).id} className="link"><TimeAgo time={JSON.parse (story).time * 1000} /></a> |
                                                        <a href={'/item/' + JSON.parse (story).id + '&goto=news'} className="link">hide</a> |
                                                        {(JSON.parse (story).kids && JSON.parse (story).kids.length) ? <a href={'/item/' + JSON.parse (story).id} className="link">{JSON.parse (story).kids.length} comments</a>: ''}
                                                    </span>
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
export default Stories;