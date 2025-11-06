import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { SelectField } from '@/components/ui/MultiSelect'
import { Textarea } from '@/components/ui/Textarea'
import { useAuth } from '@/context/AuthContext'
import DashBoardContext from '@/context/DashboardContext'
import { FeedbackContext } from '@/context/FeedBackContext'
import { useModal } from '@/hooks/useModal'
import React, { useContext, useEffect, useState } from 'react'
import { Controller, useForm, useFieldArray } from 'react-hook-form'
import { toast } from 'react-toastify'
import { useTranslation } from 'next-i18next'
import { Plus } from 'lucide-react'

function CourseForm({ handleCourse, course }) {
  const { isOpen, open, close } = useModal()
  const { t } = useTranslation('instructor')
  const { categories } = useContext(DashBoardContext)
  const { toggleProcessing } = useContext(FeedbackContext)
  const { profile } = useAuth()
  console.log
  const categoryOptions = categories?.map((category) => ({
    label: category.label,
    value: category.key
  }))

  const {
    control,
    formState: { errors },
    handleSubmit,
    reset
  } = useForm({
    defaultValues: {
      name: '',
      categories: [],
      content: '',
      requirements: [{ requirement: '', display_order: 1 }],
      course_purpose: [{ purpose: '', display_order: 1 }],
      summary: ''
    }
  })

  useEffect(() => {
    if (course && isOpen) {
      reset({
        name: course.name,
        categories: course.categories.map((category) => ({ label: category.name, value: category.id })),
        content: course.content,
        sale_price: course.sale_price,
        price: course.price,
        requirements: course.requirements?.map((requirement) => ({
          requirement: requirement.requirement,
          display_order: requirement.display_order
        })),
        course_purpose: course.course_purpose?.map((purpose) => ({
          purpose: purpose.purpose,
          display_order: purpose.display_order
        })),
        summary: course.summary
      })
    }
  }, [course, reset, isOpen])

  const {
    fields: requirementFields,
    append: appendRequirement,
    remove: removeRequirement
  } = useFieldArray({
    control,
    name: 'requirements'
  })

  const {
    fields: purposeFields,
    append: appendPurpose,
    remove: removePurpose
  } = useFieldArray({
    control,
    name: 'course_purpose'
  })

  const onSubmit = handleSubmit(async (data) => {
    try {
      toggleProcessing()
      const body = {
        name: data.name,
        sale_price: String(data.sale_price),
        price: String(data.price),
        user_id: profile.id,
        purposes: data.course_purpose,
        summary: data.summary,
        content: data.content,
        requirements: data.requirements,
        categories: data.categories.map((category) => category.value)
      }

      await handleCourse(body)

      toast.success(course ? t('updateCourseSuccess') : t('createCourseSuccess'))
      close()
      reset()
    } catch (e) {
      console.log(e)
      toast.error(course ? t('updateCourseFailed') : t('createCourseFailed'))
    } finally {
      toggleProcessing()
    }
  })

  return (
    <div>
      {course ? (
        <Button variant='fifth' icon='' className='px-3 py-1' onClick={open}>
          {t('editCourse')}
        </Button>
      ) : (
        <Button variant='secondary' className='px-3 py-2' onClick={open}>
          {t('createCourse')}
        </Button>
      )}
      <Modal isOpen={isOpen} onClose={close} title={course ? t('editCourse') : t('createCourse')}>
        <form onSubmit={onSubmit} className='course-form'>
          <Controller
            name='name'
            control={control}
            rules={{ required: t('requiredCourseName') }}
            render={({ field }) => <Input {...field} label={t('courseName')} error={errors.name?.message} />}
          />

          <div className='flex gap-4 justify-between'>
            <Controller
              name='categories'
              rules={{ required: t('requiredCategories') }}
              control={control}
              render={({ field }) => (
                <SelectField
                  {...field}
                  label={t('categories')}
                  multiple
                  options={categoryOptions}
                  error={errors.categories?.message}
                />
              )}
            />
            {/* <Controller
              name='level'
              rules={{ required: 'Vui lòng chọn cấp độ' }}
              control={control}
              render={({ field }) => <SelectField {...field} label='Cấp độ' error={errors.level?.message} />}
            /> */}
          </div>

          <div className='flex gap-4 justify-between'>
            <Controller
              name='sale_price'
              rules={{ required: t('requiredSalePrice') }}
              control={control}
              render={({ field }) => <Input {...field} label={t('Giá bán')} error={errors.sale_price?.message} />}
            />
            <Controller
              name='price'
              rules={{ required: t('requiredPrePrice') }}
              control={control}
              render={({ field }) => <Input {...field} label={t('Giá gốc')} error={errors.price?.message} />}
            />
          </div>

          <Controller
            name='content'
            rules={{ required: t('requiredContent') }}
            control={control}
            render={({ field }) => <Textarea {...field} label={t('content')} error={errors.content?.message} />}
          />

          <div className='flex flex-column gap-2 w-full'>
            {requirementFields.map((field, index) => (
              <div key={field.id} className='flex gap-2 items-start w-full'>
                <Controller
                  name={`requirements.${index}.requirement`}
                  control={control}
                  rules={{ required: t('requiredRequirement') }}
                  render={({ field }) => (
                    <Input
                      {...field}
                      label={index === 0 ? t('requirement') : undefined}
                      error={errors.requirements?.[index]?.requirement?.message}
                    />
                  )}
                />
                {requirementFields.length > 1 && (
                  <Button
                    variant='fifth'
                    className='px-3 py-1 fs-5 flex-shrink-0 align-self-end'
                    icon=''
                    type='button'
                    onClick={() => removeRequirement(index)}
                  >
                    {t('delete')}
                  </Button>
                )}
              </div>
            ))}
            <div>
              <Button
                variant='primary'
                className='px-3 py-1 fs-5 mt-2'
                type='button'
                icon=''
                onClick={() => {
                  appendRequirement({ requirement: '', display_order: requirementFields.length + 1 })
                }}
              >
                <Plus size={16} />
                {t('addRequirement')}
              </Button>
            </div>
          </div>

          <div className='flex flex-column gap-2 w-full'>
            {purposeFields.map((field, index) => (
              <div key={field.id} className='flex gap-2 items-start w-full mt-2'>
                <Controller
                  name={`course_purpose.${index}.purpose`}
                  control={control}
                  rules={{ required: t('requiredCoursePurpose') }}
                  render={({ field }) => (
                    <Input
                      {...field}
                      label={index === 0 ? t('coursePurpose') : undefined}
                      error={errors.course_purpose?.[index]?.purpose?.message}
                    />
                  )}
                />
                {purposeFields.length > 1 && (
                  <Button
                    variant='fifth'
                    className='px-3 py-1 fs-5 flex-shrink-0 align-self-end'
                    icon=''
                    type='button'
                    onClick={() => removePurpose(index)}
                  >
                    {t('delete')}
                  </Button>
                )}
              </div>
            ))}
            <div>
              <Button
                variant='primary'
                className='px-3 py-1 fs-5 mt-2'
                type='button'
                icon=''
                onClick={() => {
                  appendPurpose({ purpose: '', display_order: purposeFields.length + 1 })
                }}
              >
                <Plus size={16} />
                {t('addCoursePurpose')}
              </Button>
            </div>
          </div>

          <div className='mt-3'>
          <Controller
            name='summary'
            rules={{ required: t('requiredSummary') }}
            control={control}
              render={({ field }) => <Textarea {...field} label={t('summary')} error={errors.summary?.message} />}
            />
          </div>

          <div className='form-actions'>
            <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
              {t('cancel')}
            </Button>

            <Button variant='secondary' icon='' className='px-4 py-1' type='submit'>
              {course ? t('updateCourse') : t('createCourse')}
            </Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}

export default CourseForm
