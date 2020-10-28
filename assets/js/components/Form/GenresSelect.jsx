import React, { useEffect, useState } from 'react';
import Select from './Select';
import genresAPI from '../../services/genresAPI';
import { toast } from 'react-toastify';

const GenresSelect = ({value, error, onChange}) => {
    const [genres, setGenres] = useState([]);

    const fetchTags = async () => {
        try{
            const data = await genresAPI.findAll();
            setGenres(data);
        }catch(error){
            toast.error("Couldn't load the genres / an error occured")
        }
    }

    useEffect(() => {
        fetchTags();
    }, []);

    return ( 
        <>
            <Select
                name="genres"
                label="Genres"
                multiple={ true }
                value={ value }
                onChange={ onChange }
                error={ error }
            >
                { genres.map(genre => (
                    <option key={ genre.id } value={ `/api/genres/${genre.id}` }>{ genre.name }</option>
                )) }
            </Select>
        </>
     );
}
 
export default GenresSelect; 