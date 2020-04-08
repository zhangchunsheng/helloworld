package com.luomor.repository.primary;

import com.luomor.model.User;
import org.springframework.data.mongodb.repository.MongoRepository;

/**
 * @author luomor
 */
public interface PrimaryRepository extends MongoRepository<User, String> {
}
