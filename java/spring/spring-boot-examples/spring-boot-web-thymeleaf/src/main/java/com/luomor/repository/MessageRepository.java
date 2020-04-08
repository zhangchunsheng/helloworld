
package com.luomor.repository;

import com.luomor.model.Message;

public interface MessageRepository {

	Iterable<Message> findAll();

	Message save(Message message);

	Message findMessage(Long id);

	void deleteMessage(Long id);

}
