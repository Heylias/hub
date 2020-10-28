import './../css/app.scss';
import authAPI from './services/authAPI';
import AuthContext from './contexts/AuthContext';
import 'react-toastify/dist/ReactToastify.css';

//modules
import { ToastContainer, toast } from 'react-toastify';
import React, { useState } from 'react';
import ReactDom from 'react-dom';
import { HashRouter, Switch, Route, withRouter } from 'react-router-dom';

//components
import Navbar from './components/Navbar';
import PrivateRoute from './components/PrivateRoute';

// other pages
import HomePage from './pages/HomePage';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';

//edit pages
import EditFanfictionPage from './pages/itemPages/EditFanfictionPage';
import EditUserPage from './pages/itemPages/EditUserPage';
import PasswordChangePage from './pages/itemPages/PasswordChangePage';
import UploadChapterPage from './pages/itemPages/UploadChapterPage';

//item pages
import FanfictionPage from './pages/itemPages/FanfictionPage';
import UserPage from './pages/itemPages/UserPage';

//collection pages
import FanfictionsPage from './pages/collectionPages/FanfictionsPage';
import RecentUploadPage from './pages/collectionPages/RecentUploadsPage';
import BestRatedPage from './pages/collectionPages/BestRatedPage';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js')

authAPI.setup()

const App = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(authAPI.isAuthenticated())

    const NavbarWithRouter = withRouter(Navbar)

    const contextValue = {
        isAuthenticated: isAuthenticated,
        setIsAuthenticated : setIsAuthenticated
    }

    return (
        <AuthContext.Provider value={ contextValue }>
            <HashRouter>
                <NavbarWithRouter/>
                <main className="container pt-5">
                    <Switch>
                        <PrivateRoute path="/fanfictions/new" component={ EditFanfictionPage } />
                        <PrivateRoute path="/fanfictions/:id/edit" component={ EditFanfictionPage } />
                        <PrivateRoute path="/fanfictions/:id/upload" component={ UploadChapterPage } />
                        <Route path="/fanfictions/best" component={ BestRatedPage } />
                        <Route path="/fanfictions/latest" component={ RecentUploadPage } />
                        <Route path="/fanfictions/:id" component={ FanfictionPage } />
                        <Route path="/fanfictions" component={ FanfictionsPage } />
                        <PrivateRoute path="/users/:id/password" component={ PasswordChangePage } />
                        <PrivateRoute path="/users/:id/edit" component={ EditUserPage } />
                        <Route path="/users/:id" component={ UserPage } />
                        <Route path="/register" component={ RegisterPage }/>
                        <Route path="/login" component={ LoginPage }/>
                        <Route path="/" component={ HomePage } />
                    </Switch>
                </main>
            </HashRouter>
            <ToastContainer position={ toast.POSITION.BOTTOM_LEFT } />
        </AuthContext.Provider>
    )
}

const rootElement = document.querySelector('#app')
ReactDom.render(<App />, rootElement)