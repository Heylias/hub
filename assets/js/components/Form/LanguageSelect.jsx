import React, { useEffect, useState } from 'react';
import { toast } from 'react-toastify';
import languageAPI from '../../services/languageAPI';
import Select from './Select';

const LanguageSelect = ({value, error, onChange}) => {
    const [languages, setLanguages] = useState([]);

    const fetchLanguages = async () => {
        try{
            const data = await languageAPI.findAll();
            setLanguages(data);
        }catch(error){
            toast.error("Couldn't load the languages / an error occured")
        }
    }

    useEffect(() => {
        fetchLanguages();
    }, []);

    return ( 
        <>
            <Select 
                name="language"
                label="Language"
                value={ value }
                onChange={ onChange }
                error={ error }
            >
                { languages.map(language => 
                    <option key={ language.id } value={ `/api/languages/${language.id}` }>{ language.short } { language.name }</option>
                 ) }
            </Select>
        </>
     );
}
 
export default LanguageSelect;