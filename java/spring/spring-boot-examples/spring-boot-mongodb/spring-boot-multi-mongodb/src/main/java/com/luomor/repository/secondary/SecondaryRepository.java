package com.luomor.repository.secondary;

import com.luomor.model.User;
import org.springframework.data.mongodb.repository.MongoRepository;

/**
 * @author luomor
 */
public interface SecondaryRepository extends MongoRepository<User, String> {
}
