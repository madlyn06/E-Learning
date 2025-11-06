import { Input } from '@/components/ui/Input'
import { SelectField } from '@/components/ui/MultiSelect'
import { Textarea } from '@/components/ui/Textarea'
import DashBoardContext from '@/context/DashboardContext'
import { useLoading } from '@/hooks/useLoading'
import React, { useContext } from 'react'
import { Controller, useFormContext } from 'react-hook-form'

function BasicInfo({ tab }) {
  const {
    formState: { errors },
    control
  } = useFormContext()
  const { categories } = useContext(DashBoardContext)

  return (
    <div
      style={{
        display: tab === 'basic' ? 'block' : 'none'
      }}
      className='widget-content-inner active'
    >
      <Controller
        control={control}
        name='name'
        rules={{
          required: 'Vui lòng nhập tên khoá học'
        }}
        render={({ field }) => <Input {...field} label='Tên khoá học' error={errors.name?.message} />}
      />
      <label className='tf-field-label fs-15 mb-2'>Danh mục khoá học</label>
      <Controller
        control={control}
        name='categories'
        rules={{
          required: 'Vui lòng chọn danh mục'
        }}
        render={({ field }) => (
          <SelectField
            options={categories.map((item) => ({ value: item.id, label: item.label }))}
            {...field}
            label='Chọn danh mục'
            multiple
            error={errors.categories?.message}
          />
        )}
      />

      {/* <Controller
          control={control}
          name="level"
          rules={{
            required: "Course level is required"
          }}
          render={({ field }) => <SelectField multiple {...field} label="Course level" error={errors.level?.message} />}
        /> */}

      <Controller
        control={control}
        name='content'
        rules={{
          required: 'Vui lòng nhập mô tả khoá học'
        }}
        render={({ field }) => <Textarea {...field} label='Mô tả khoá học' error={errors.content?.message} />}
      />
    </div>
  )
}

export default BasicInfo
