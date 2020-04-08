package com.luomor.sevice.impl;

import com.luomor.dao.UserInfoDao;
import com.luomor.model.UserInfo;
import com.luomor.sevice.UserInfoService;
import org.springframework.stereotype.Service;

import javax.annotation.Resource;

@Service
public class UserInfoServiceImpl implements UserInfoService {
    @Resource
    private UserInfoDao userInfoDao;
    @Override
    public UserInfo findByUsername(String username) {
        System.out.println("UserInfoServiceImpl.findByUsername()");
        return userInfoDao.findByUsername(username);
    }
}