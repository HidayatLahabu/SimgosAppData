import React from 'react';
import Select from 'react-select';

export default function SelectInput({ className = '', options = [], ...props }) {
    const customStyles = {
        control: (provided, state) => ({
            ...provided,
            borderWidth: state.isFocused ? '1px' : '0',
            borderColor: 'rgb(79 70 229)',
            backgroundColor: 'rgb(3 7 18)',
            color: 'rgb(229 231 235)',
            boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
            '&:hover': {
                borderColor: 'rgb(79 70 229)',
            },
        }),
        menu: (provided) => ({
            ...provided,
            backgroundColor: 'rgb(30 27 75)',
        }),
        option: (provided, state) => ({
            ...provided,
            backgroundColor: state.isSelected ? 'rgb(55 65 81)' : 'rgb(55 65 81)',
            color: state.isSelected ? 'gray-200' : 'rgb(229 231 235)',
            '&:hover': {
                backgroundColor: '#030712',
            },
        }),
        singleValue: (provided) => ({
            ...provided,
            color: 'gray-200',
        }),
        input: (provided) => ({
            ...provided,
            color: 'gray-200',
        }),
    };

    return (
        <Select
            {...props}
            options={options}
            className={className}
            styles={customStyles}
            menuPlacement="auto"
            menuPortalTarget={document.body}
            menuPosition="fixed"
            isClearable={true}
        />
    );
}
