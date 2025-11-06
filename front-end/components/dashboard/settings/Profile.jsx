import { Input } from '@/components/ui/Input'
import { Textarea } from '@/components/ui/Textarea'
import { useLoading } from '@/hooks/useLoading'
import apiFetch from '@/utils/request'
import Image from 'next/image'
import React from 'react'
import { useForm, Controller } from 'react-hook-form'
import { toast } from 'react-toastify'
import { useAuth } from '@/context/AuthContext'
import { useProfile } from '@/hooks/useProfile'

function Profile() {
  const { loading, withLoading } = useLoading()
  const { profile } = useAuth()
  const { refresh } = useProfile()

  const {
    handleSubmit,
    formState: { errors },
    control
  } = useForm({
    defaultValues: {
      first_name: profile?.first_name,
      last_name: profile?.last_name,
      phone_number: profile?.phone_number,
      name: profile?.name,
      skill: profile?.skill,
      bio: profile?.bio
    }
  })

  const onSubmit = handleSubmit(async (data) => {
    try {
      await withLoading(async () => {
        await apiFetch.put('v1/elearning/users/profile', {
          first_name: data.firstName,
          last_name: data.lastName,
          phone_number: data.phone_number,
          name: data.name,
          skill: data.skill,
          bio: data.bio
        })
        toast.success('Profile updated successfully')
        refresh()
      })
    } catch (error) {
      console.log(error.message)
      toast.error('Profile updated failed')
    }
  })

  return (
    <div className='widget-content-inner active'>
      <div className='row'>
        <div className='profile-wrap'>
          <div className='profile-img'>
            <Image id='profile-img' src='/images/avatar/review-1.png' data-='' alt='' width={101} height={100} />
          </div>
          <div className='profile-info'>
            <h4>Your avatar</h4>
            <label id='name-file'>PNG or JPG no bigger than 800px wide and tall.</label>
          </div>
          <div className='profile-btn'>
            <input id='file-input' type='file' />
            <button className='btn-update tf-button-default'>
              Update <i className='icon-arrow-top-right' />
            </button>
            <a href='#' className='btn-delete tf-button-default'>
              Delete <i className='icon-arrow-top-right' />
            </a>
          </div>
        </div>
      </div>
      <form onSubmit={onSubmit} className='shop-checkout'>
        <div className='cols'>
          <Controller
            name='first_name'
            control={control}
            render={({ field }) => <Input {...field} label='First Name' error={errors.firstName?.message} />}
          />

          <Controller
            name='last_name'
            control={control}
            render={({ field }) => <Input {...field} label='Last Name' error={errors.firstName?.message} />}
          />
        </div>
        <div className='cols'>
          <Controller
            name='name'
            control={control}
            render={({ field }) => <Input {...field} label='Username' error={errors.name?.message} />}
          />

          <Controller
            name='phone_number'
            control={control}
            render={({ field }) => <Input {...field} label='Phone Number' error={errors.name?.message} />}
          />
        </div>
        <Controller
          name='skill'
          control={control}
          render={({ field }) => <Input {...field} label='Skill/Occupation' error={errors.name?.message} />}
        />

        <Controller
          name='bio'
          control={control}
          render={({ field }) => <Textarea {...field} label='Message' error={errors.name?.message} />}
        />
        <button href='' type='submit' className='tf-btn' disabled={loading}>
          Update Profile <i className='icon-arrow-top-right' />
        </button>
      </form>
    </div>
  )
}

export default Profile
