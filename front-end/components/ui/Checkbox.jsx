import React, { useId, forwardRef } from 'react'

const Checkbox = forwardRef(
  (
    {
      label,
      name,
      value,
      onChange,
      disabled = false,
      className = '',
      labelClassName = '',
      required = false,
      error,
      ...rest
    },
    ref
  ) => {
    const id = useId()
    return (
      <div className={`checkbox-item ${className}`}>
        <label htmlFor={id} className={labelClassName}>
          {label && <p className='fs-15'>{label}</p>}
          <input
            ref={ref}
            name={name}
            id={id}
            type='checkbox'
            checked={value}
            onChange={onChange}
            disabled={disabled}
            required={required}
            {...rest}
          />
          <span className='btn-checkbox' />
          {error && <p className='form-error-message'>{error}</p>}
        </label>
      </div>
    )
  }
)

Checkbox.displayName = 'Checkbox'

export default Checkbox
