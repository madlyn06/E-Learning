import { useState } from 'react'
import AdditionalInfo from './AdditionalInfo'
import BasicInfo from './BasicInfo'
import Curriculum from './Curriculum'
import { Button } from '@/components/ui/Button'
import { useForm, FormProvider } from 'react-hook-form'

const validateFields = {
  basic: ['categories', 'content', 'name'],
  curriculum: ['sections'],
  additional: []
}

export default function AddCourses() {
  const [tab, setTab] = useState('basic')

  const form = useForm({
    defaultValues: {
      name: '',
      categories: '',
      content: '',
      sections: []
    },
    mode: 'onChange'
  })

  const onSubmit = form.handleSubmit(async (data) => {
    console.log(data)
  })

  const handleTabChange = async (direction) => {
    const nextMap = {
      basic: 'curriculum',
      curriculum: 'additional'
    }

    const prevMap = {
      curriculum: 'basic',
      additional: 'curriculum'
    }

    if (direction === 'next' && nextMap[tab]) {
      const isValid = await form.trigger(validateFields[tab])
      if (!isValid) {
        return
      }
      setTab(nextMap[tab])
    } else if (direction === 'prev' && prevMap[tab]) {
      setTab(prevMap[tab])
    }
  }
  return (
    <div className='col-xl-9'>
      <section className='section-add-course-right section-right wg-form'>
        <div className='box'>
          <div className='widget-tabs style-small'>
            <ul className='widget-menu-tab overflow-x-auto pd-40'>
              <li className={`item-title ${tab === 'basic' && 'active'}`} onClick={() => setTab('basic')}>
                Thông tin cơ bản
              </li>
              {/* <li className='item-title'>Media</li> */}
              <li className={`item-title ${tab === 'curriculum' && 'active'}`} onClick={() => setTab('curriculum')}>
                Chương trình học
              </li>
              <li className={`item-title ${tab === 'additional' && 'active'}`} onClick={() => setTab('additional')}>
                Thông tin bổ sung
              </li>
            </ul>
            <FormProvider {...form}>
              <form onSubmit={onSubmit} className='shop-checkout'>
                <div className='widget-content-tab'>
                  <BasicInfo tab={tab} />
                  <Curriculum tab={tab} />
                  <AdditionalInfo tab={tab} />
                </div>
                <div className={`flex items-center ${tab === 'basic' ? 'justify-end' : 'justify-between'}`}>
                  {tab !== 'basic' && (
                    <Button variant='third' type='button' onClick={() => handleTabChange('prev')}>
                      Previous
                    </Button>
                  )}

                  {tab !== 'additional' && (
                    <Button type='button' onClick={() => handleTabChange('next')}>
                      Next
                    </Button>
                  )}

                  {tab === 'additional' && (
                    <Button variant='secondary' type='button'>
                      Create Course
                    </Button>
                  )}
                </div>
              </form>
            </FormProvider>
          </div>
        </div>
      </section>
    </div>
  )
}
