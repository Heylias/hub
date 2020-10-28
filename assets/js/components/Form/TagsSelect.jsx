import React, { useEffect, useState } from 'react';
import Select from './Select';
import tagsAPI from '../../services/tagsAPI';
import { toast } from 'react-toastify';

const TagsSelect = ({value, error, onChange}) => {
    const [tags, setTags] = useState([]);

    const fetchTags = async () => {
        try{
            const data = await tagsAPI.findAll();
            setTags(data);
        }catch(error){
            toast.error("Couldn't load the tags / an error occured")
        }
    }

    useEffect(() => {
        fetchTags();
    }, []);

    return ( 
        <>
            <Select
                name="tags"
                label="Tags"
                multiple={ true }
                value={ value }
                onChange={ onChange }
                error={ error }
            >
                { tags.map(tag => (
                    <option key={ tag.id } value={ `/api/tags/${tag.id}` }>{ tag.name }</option>
                )) }
            </Select>
        </>
     );
}
 
export default TagsSelect; 