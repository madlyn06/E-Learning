import { Input } from '@/components/ui/Input'
import { Textarea } from '@/components/ui/Textarea'
import React from 'react'
import { Controller, useFormContext } from 'react-hook-form'

function AdditionalInfo({ tab }) {
  const {
    formState: { errors },
    control
  } = useFormContext()

  return (
    <div
      style={{
        display: tab === 'additional' ? 'block' : 'none'
      }}
      className='widget-content-inner'
    >
      <Controller
        control={control}
        name='email'
        rules={{
          required: 'Email is required',
          pattern: {
            value: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            message: 'Invalid email address'
          }
        }}
        render={({ field }) => <Input {...field} label='Email' error={errors.email?.message} />}
      />
      <Controller
        control={control}
        name='tags'
        rules={{
          required: 'Tags is required'
        }}
        render={({ field }) => <Input {...field} label='Tags' error={errors.tags?.message} />}
      />
      <Controller
        control={control}
        name='languange'
        rules={{
          required: 'Languange is required'
        }}
        render={({ field }) => <Input {...field} label='Languange' error={errors.languange?.message} />}
      />

      <Controller
        control={control}
        name='course_tags'
        rules={{
          required: 'Course Tags is required'
        }}
        render={({ field }) => <Textarea {...field} label='Course Tags' error={errors.course_tags?.message} />}
      />
    </div>
  )
}

export default AdditionalInfo
