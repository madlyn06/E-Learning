import React, { forwardRef } from 'react'

function RadioButtonBase({ options, name, label, error, value, onChange, ...rest }, ref) {
  return (
    <div className='wg-radiobox'>
      <div className='head w-800'>
        <label className='fs-15'>{label}</label>
      </div>
      <div className='answers w-800 flex flex-column gap-3'>
        {options?.map((option, index) => (
          <div key={option.value || index} className='radio-item'>
            <label htmlFor={`${name}-${option.value || index}`}>
              <p>{option.label}</p>
              <input
                ref={ref}
                name={name}
                type='radio'
                id={`${name}-${option.value || index}`}
                value={option.value}
                checked={Number(value) === option.value}
                onChange={(e) => {
                  if (onChange) {
                    onChange(e.target.value)
                  }
                }}
                {...rest}
              />
              <span className='btn-radio' />
            </label>
          </div>
        ))}
      </div>
      {error && <p className='form-error-message'>{error}</p>}
    </div>
  )
}

const RadioButton = forwardRef(RadioButtonBase)
RadioButton.displayName = 'RadioButton'

export default RadioButton
