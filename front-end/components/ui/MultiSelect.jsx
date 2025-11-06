import { forwardRef, useId } from 'react'
import Select from 'react-select'

// eslint-disable-next-line react/display-name
export const SelectField = forwardRef(
  ({ label, multiple = false, className = '', error, placeholder = '', options = [], ...rest }, ref) => {
    const id = useId()
    return (
      <fieldset className={`tf-field ${className}`}>
        <Select
          styles={() => {
            return {
              control: (base) => ({
                ...base,
                borderColor: error ? '#ff4d4f' : base.borderColor,
                boxShadow: error ? '0 0 0 1px #ff4d4f' : base.boxShadow,
                '&:hover': {
                  borderColor: error ? '#ff4d4f' : base.borderColor
                }
              })
            }
          }}
          options={options}
          placeholder={label}
          classNamePrefix='tf-select'
          isMulti={multiple}
          ref={ref}
          inputId={id} // kết nối label với input
          instanceId={id}
          {...rest}
        />

        {/* {label && (
          <label className='tf-field-label fs-15' htmlFor={id}>
            {label}
          </label>
        )} */}

        {error && <p className='form-error-message'>{error}</p>}
      </fieldset>
    )
  }
)
