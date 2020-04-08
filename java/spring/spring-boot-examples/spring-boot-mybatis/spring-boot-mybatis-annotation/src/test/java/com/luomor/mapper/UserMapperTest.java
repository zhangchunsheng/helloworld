package com.luomor.mapper;

import java.util.List;

import com.luomor.model.User;
import org.junit.Assert;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.junit4.SpringRunner;

import com.luomor.enums.UserSexEnum;

@RunWith(SpringRunner.class)
@SpringBootTest
public class UserMapperTest {

	@Autowired
	private UserMapper userMapper;

	@Test
	public void testInsert() throws Exception {
		userMapper.insert(new User("aa1", "a123456", UserSexEnum.MAN));
		userMapper.insert(new User("bb1", "b123456", UserSexEnum.WOMAN));
		userMapper.insert(new User("cc1", "b123456", UserSexEnum.WOMAN));

		Assert.assertEquals(3, userMapper.getAll().size());
	}

	@Test
	public void testQuery() throws Exception {
		List<User> users = userMapper.getAll();
		System.out.println(users.toString());
	}
	
	
	@Test
	public void testUpdate() throws Exception {
		User user = userMapper.getOne(30l);
		System.out.println(user.toString());
		user.setNickName("luomor");
		userMapper.update(user);
		Assert.assertTrue(("luomor".equals(userMapper.getOne(30l).getNickName())));
	}

}