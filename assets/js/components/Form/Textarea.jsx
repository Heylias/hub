import React from 'react';

const Textarea = ({ name, label, value, onChange, placeholder = "", error="" }) => {
    return ( 
        <div className="form-group">
            <label htmlFor={ name }>{ label }</label>
            <textarea
                value={ value }
                onChange={ onChange }
                placeholder={ placeholder || label }
                name={ name }
                id={ name }
                rows="5"
                cols="100"
                className={ "form-control" + (error && " is-invalid") }
            />
            { error && (
                <p className="invalid-feedback">{ error }</p>
            )
            }
        </div>
     );
}
 
export default Textarea;