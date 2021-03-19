import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Stories from './Stories';
import Comments from './Comments';
import Logo from '../../images/favicon.ico';
    
class Home extends Component {
    
    render() {
        return (
            <div className="main-container">
                <nav className="navbar navbar-expand-lg navbar-dark bg-orange">
                   
                    <Link className={"navbar-brand"} to={"/"}> 
                    <img className="logo" src={Logo} alt="Hacker News" /> Hacker News </Link>
                    <div className="collapse navbar-collapse" id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/newcomments"}> comments </Link>
                            </li>
                        </ul>
                    </div>
                    <Link className={"navbar-login"} to={"/"}>login</Link>
                </nav>
                <Switch>
                    <Redirect exact from="/" to="/all" />
                    <Route path="/all" component={Stories} />
                    <Route path="/newcomments" component={Comments} />
                </Switch>
                <div>
                    <section className="footSection container">
                        <p>Applications are open for YC Summer 2021</p>
                        <ul>
                            <li><Link to={"/"}>Guidelines</Link> | </li>
                            <li><Link to={"/"}>FAQ</Link> | </li>
                            <li><Link to={"/"}>Lists</Link> | </li>
                            <li><Link className="api" to={"/"}>API</Link> | </li>
                            <li><Link to={"/"}>Security</Link> | </li>
                            <li><Link to={"/"}>Legal</Link> | </li>
                            <li><Link to={"/"}>Apply to YC</Link> | </li>
                            <li><Link to={"/"}>Contact</Link></li>
                        </ul>
                        <div className="search">
                            <span>Search:</span> <input type="text" />
                        </div>
                    </section>
                </div>
            </div>
        )
    }
}
    
export default Home;