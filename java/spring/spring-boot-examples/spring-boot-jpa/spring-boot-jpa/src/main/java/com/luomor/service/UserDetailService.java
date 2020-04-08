package com.luomor.service;

import com.luomor.model.UserDetail;
import com.luomor.param.UserDetailParam;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

public interface UserDetailService {
    public Page<UserDetail> findByCondition(UserDetailParam detailParam, Pageable pageable);
}
