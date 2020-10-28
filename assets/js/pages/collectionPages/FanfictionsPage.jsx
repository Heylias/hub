import React, { useEffect, useState } from 'react';
import fanfictionsAPI from '../../services/fanfictionsAPI';
import tagsAPI from '../../services/tagsAPI';
import Pagination from '../../components/Pagination';
import { toast } from 'react-toastify';
import FanficItem from '../../components/Modals/FanfictionItem2';
import DotLoader from '../../components/Loader/DotLoader';

const FanfictionsPage = () => {
    const [fanfictions, setFanfictions] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);

    const [tags, setTags] = useState([]);

    const [search, setSearch] = useState("");

    const [tagSearch, setTagSearch] = useState([]);

    const [loading, setLoading] = useState(true);

    const fetchData = async () => {
        try{
            const data = await fanfictionsAPI.findAll();
            const tags = await tagsAPI.findAll();
            setFanfictions(data);
            setTags(tags);
            setLoading(false);
        }catch(error){
           toast.error("An unexpected error occured");
        }
    }
    
    useEffect(() => {
        fetchData();
    }, [currentPage, tagSearch]);

    const handleSearch = event => {
        const value = event.currentTarget.value
        setSearch(value)
        setCurrentPage(1)
    }

    const handlePageChange = (page) => {
        setFanfictions([]);
        setCurrentPage(page);
    }

    const filteredFanfictions = fanfictions.filter(f =>
        f.title.toLowerCase().includes(search.toLowerCase())
    )


    const itemsPerPage = 10;

    const paginatedFanfictions = Pagination.getData(filteredFanfictions, currentPage, itemsPerPage);

    return (
        <>
            <div className="d-flex justify-content-between align-items-center">
                <h1>Fanfictions List</h1>
            </div>
            <div className="form-group">
                <input type="text" className="form-control" placeholder="Search..." onChange={ handleSearch } value={ search } />
            </div>
            <ul className="fanfic_list">
                {(!loading) ? (
                paginatedFanfictions.map((fanfiction, index) => (
                    <li className="fanfic_item_2" key={ index }>
                        <FanficItem
                            fanfiction={ fanfiction }
                        />
                    </li>
                ))) : (
                    <DotLoader />
                )}
            </ul>
            { itemsPerPage < filteredFanfictions.length &&
                <Pagination 
                    currentPage={ currentPage }
                    itemsPerPage={ itemsPerPage }
                    length={ filteredFanfictions.length }
                    onPageChanged={ handlePageChange }
                />
            }
            { (paginatedFanfictions.length === 0 && !loading) && <span>No fanfictions matches your search request.</span> }
        </>
    );
}

export default FanfictionsPage;