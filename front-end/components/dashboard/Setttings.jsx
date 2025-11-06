import React from 'react'
import ChangePassword from './settings/ChangePassword'
import Social from './settings/Social'
import Profile from './settings/Profile'


export default function Setttings() {
  return (
    <div className='col-xl-9 col-lg-12'>
      <div className='section-setting-right section-right'>
        <div className='box'>
          <div className='widget-tabs style-small'>
            <ul className='widget-menu-tab overflow-x-auto'>
              <li className='item-title active'>Profile</li>
              <li className='item-title'>Password</li>
              <li className='item-title'>Social</li>
            </ul>
            <div className='widget-content-tab'>
              {/* Update Profile form */}
              <Profile />
              {/* Update Password form */}
              <ChangePassword />
              {/* Update Social form */}
              <Social />
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
