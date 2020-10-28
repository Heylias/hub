import React, { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import authAPI from '../services/authAPI'
import AuthContext from '../contexts/AuthContext'
import { toast } from 'react-toastify'
import { Dropdown, DropdownToggle, DropdownMenu, DropdownItem, UncontrolledDropdown, Navbar, NavbarToggler, Collapse, Nav, NavItem } from 'reactstrap';
import JwtDecode from 'jwt-decode'

const Navibar = (props) => {
    const { isAuthenticated, setIsAuthenticated } = useContext(AuthContext)

    const handleLogout = () => {
        authAPI.logout()
        setIsAuthenticated(false)
        toast.info("Logged out successfully")
        props.history.push("/login")
    }

    const token = window.localStorage.getItem("authToken")

    const [currentUserId, setCurrentUserId] = useState()
    const [currentUser, setCurrentUser] = useState()

    useEffect(() => {
        if(token){
            const jwtData = JwtDecode(token)
            setCurrentUserId(jwtData.user)
            setCurrentUser(jwtData.pseudonym)
        }
    }, [])

    const [isOpen, setIsOpen] = useState(false);

    const toggle = () => setIsOpen(!isOpen);

    return ( 
        <Navbar className="px-5" color="primary" dark expand="md">
            <Link className="navbar-brand" to="/">Hubfiction</Link>
            <NavbarToggler onClick={ toggle } />
            <Collapse isOpen={ isOpen } navbar>
                <Nav className="mr-auto" navbar>
                    <NavItem>
                        <Link to="/fanfictions/latest"><i className="far fa-clock" />Recent Uploads</Link>
                    </NavItem>
                    <NavItem>
                        <Link to="/fanfictions/best"><i className="fas fa-trophy" />Best ranked</Link>
                    </NavItem>
                    <NavItem>
                        <Link to="/fanfictions"><i className="fas fa-search" />Filterable list</Link>
                    </NavItem>
                </Nav>
                <Nav className="ml-auto" navbar>
                    {(!isAuthenticated) ? (
                        <>
                            <li className="nav-item">
                                <Link to="/register" className="nav-link">Register</Link>
                            </li>
                            <li className="nav-item">
                                <Link to="/login" className="btn btn-success">Login</Link>
                            </li>
                        </>
                    ) : (
                        <Dropdown>
                            <UncontrolledDropdown setActiveFromChild>
                                <DropdownToggle tag="a" className="nav-link" id="profile-link" caret>
                                    <i className="fas fa-user" />{ currentUser }
                                </DropdownToggle>
                                <DropdownMenu right>
                                    <DropdownItem tag="a" href={`/#/users/${ currentUserId }`} >Profile</DropdownItem>
                                    <DropdownItem divider />
                                    <DropdownItem tag="a" href="/#/fanfictions/new">Create a fanfiction</DropdownItem>
                                    <DropdownItem divider />
                                    <DropdownItem tag="a" onClick={ handleLogout }>Log out</DropdownItem>
                                </DropdownMenu>
                            </UncontrolledDropdown>
                        </Dropdown>
                    )}
                </Nav>
            </Collapse>
        </Navbar>
        // <nav className="navbar navbar-expand-lg bg-primary px-5">
        //     <Link className="navbar-brand" to="/">Hubfiction</Link>
        //     <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        //         <span className="navbar-toggler-icon"></span>
        //     </button>

        //     <div className="collapse navbar-collapse" id="navbarColor01">
        //         <ul className="navbar-nav mr-auto">
        //             <li className="nav-item">
        //                 <Link to="/fanfictions/latest"><i className="far fa-clock" />Recent Uploads</Link>
        //             </li>
        //             <li className="nav-item">
        //                 <Link to="/fanfictions/best"><i className="fas fa-trophy" />Best ranked</Link>
        //             </li>
        //             <li className="nav-item">
        //                 <Link to="/fanfictions"><i className="fas fa-search" />Filterable list</Link>
        //             </li>
        //         </ul>
        //         <ul className="navbar-nav ml-auto">
        //             {(!isAuthenticated) ? (
        //                 <>
        //                     <li className="nav-item">
        //                         <Link to="/register" className="nav-link">Register</Link>
        //                     </li>
        //                     <li className="nav-item">
        //                         <Link to="/login" className="btn btn-success">Login</Link>
        //                     </li>
        //                 </>
        //             ) : (
        //                 <Dropdown>
        //                     <UncontrolledDropdown setActiveFromChild>
        //                         <DropdownToggle tag="a" className="nav-link" id="profile-link" caret>
        //                             <i className="fas fa-user" />{ currentUser }
        //                         </DropdownToggle>
        //                         <DropdownMenu right>
        //                             <DropdownItem tag="a" href={`/#/users/${ currentUserId }`} >Profile</DropdownItem>
        //                             <DropdownItem divider />
        //                             <DropdownItem tag="a" href="/#/fanfictions/new">Create a fanfiction</DropdownItem>
        //                             <DropdownItem divider />
        //                             <DropdownItem tag="a" onClick={ handleLogout }>Log out</DropdownItem>
        //                         </DropdownMenu>
        //                     </UncontrolledDropdown>
        //                 </Dropdown>
        //             )}
        //         </ul>
        //     </div>
        // </nav>
     );
}
 
export default Navibar;