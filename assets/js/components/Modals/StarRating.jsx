import React, { useState } from 'react';
import BeautyStars from 'beauty-stars';

const StarRating = ({edit, rating}) => {
    return ( 
        <>
            <BeautyStars
                activeColor="#E44D2E"
                editable={ edit }
                maxStars={ 5 }
                size={ 15 }
                value={ rating }
                gap={ 5 }
            />
        </>
     );
}
 
export default StarRating;