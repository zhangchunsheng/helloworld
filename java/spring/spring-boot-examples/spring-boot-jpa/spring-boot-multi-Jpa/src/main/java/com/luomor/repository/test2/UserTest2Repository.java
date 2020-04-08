package com.luomor.repository.test2;

import com.luomor.model.User;
import org.springframework.data.jpa.repository.JpaRepository;


public interface UserTest2Repository extends JpaRepository<User, Long> {
    User findById(long id);
    User findByUserName(String userName);
    User findByUserNameOrEmail(String username, String email);
}