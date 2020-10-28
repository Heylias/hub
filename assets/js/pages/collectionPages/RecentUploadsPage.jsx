import React, { useEffect, useState } from 'react';
import { toast } from 'react-toastify';
import DotLoader from '../../components/Loader/DotLoader';
import FanfictionItem from '../../components/Modals/FanfictionItem2';
import Pagination from '../../components/Pagination';
import fanfictionsAPI from '../../services/fanfictionsAPI';

const RecentUploadPage = () => {
    const [fanfictions, setFanfictions] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);

    const [loading, setLoading] = useState(true);

    const handlePageChange = (page) => {
        setFanfictions([]);
        setCurrentPage(page);
    }

    const itemsPerPage = 10;

    const paginatedFanfictions = Pagination.getData(fanfictions, currentPage, itemsPerPage);

    const fetchRecentUploads = async () => {
        try{
            const data = await fanfictionsAPI.recentUploads();
            setFanfictions(data);
            setLoading(false);
        }catch(error){
           toast.error("Impossible to load the fanfictions / An unexpected error occured");
        }
    }

    useEffect(() => {
        fetchRecentUploads();
    }, [currentPage]);

    return ( 
        <>
            <div className="d-flex justify-content-between align-items-center">
                <h1>Recent Uploads</h1>
            </div>
            <ul className="fanfic_list">
            {(!loading) ? (
                paginatedFanfictions.map((fanfiction, index) => (
                    <li className="fanfic_item_2" key={ index }>
                        <FanfictionItem
                            fanfiction={ fanfiction }
                        />
                    </li>
                ))) : (
                    <DotLoader />
                )}
            </ul>
            { itemsPerPage < fanfictions.length &&
                <Pagination 
                    currentPage={ currentPage }
                    itemsPerPage={ itemsPerPage }
                    length={ fanfictions.length }
                    onPageChanged={ handlePageChange }
                />
            }
        </>
     );
}
 
export default RecentUploadPage;