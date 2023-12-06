import React, { useState } from 'react';
import WhiteShield from './resources/white_shield.png';
import SupportIcon from './resources/support';
import CallIcon from './resources/phone';
import FooterQuickItem from './components/FooterQuickItem';
import EmailIcon from './resources/email';
import HomeMerchantHours from './hours.json';

const faqs = {
  "id": "_40",
  "title": "Frequently Asked Questions",
  "url": "http:\/\/cardtest.local\/faqs\/",
  "classes": [""],
  "children": []
}

const email = {
  "id": "_40",
  "title": "campuscard@wustl.edu",
  "url": "http:\/\/cardtest.local\/faqs\/",
  "classes": [""],
  "children": []
}

const phone = {
  "id": "_40",
  "title": "314-935-8000",
  "url": "http:\/\/cardtest.local\/faqs\/",
  "classes": [""],
  "children": []
}

function Footer(props) {

  const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]

  return (
    <>
      <footer>
        <div id='footer_core'>
          <img id='footer_shield' src={WhiteShield} alt="Mobile Shield of Washington University in St. Louis" />
          <div className='footer_core_content'>
            <h3>University Service Center<br />Washington University in St. Louis</h3>
            <p>Our office is dedicated to upholding a secure & robust card program for all students, faculty, staff, guests, and affiliated contractors of Washington University in St. Louis. Load funds, swipe, and enjoy on-campus merchants.</p>
          </div>
          <div className='footer_core_contact_us'>
            <h6>Contact Us</h6>
            <FooterQuickItem item={faqs}><SupportIcon color="#ffffff" /></FooterQuickItem>
            <FooterQuickItem item={email}><EmailIcon color="#ffffff" /></FooterQuickItem>
            <FooterQuickItem item={phone}><CallIcon color="#ffffff" /></FooterQuickItem>
          </div>
        </div>
        <div id='footer_middle'>
          <h6>Office Information</h6>
          <p>Ann W. Olin Women’s Building, Suite 002<br />Danforth Campus, Washington University in St. Louis, Missouri 63105</p>

          <div className='footer_hours_current'>
            {daysOfWeek.map((dow, index) => {
              const scheme = HomeMerchantHours[0].days[index];
              console.log(scheme);
              return (
                <div className='footer_day_of_week_hours'>
                  <span>{dow}</span>
                  {
                    scheme === true ? (
                      <span>Open</span>
                    ) : scheme === false ? (
                      <span>Closed</span>
                    ) : (
                      <span>{HomeMerchantHours[0].schemes[scheme].asString}</span>
                    )
                  }
                </div>
              )
            })}
          </div>

          <p>Washington University in St. Louis<br />One Brookings Drive, St. Louis, MO 63105<br />MSC 1055-156-3</p>
        </div>

      </footer>
    </>
  )
};


export default Footer;
