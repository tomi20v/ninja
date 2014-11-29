this folder contains data for snapshot testing

snapshot testing is just a weird idea I implemented for fun. it's like...
stub a method of a class (not object!). the stub shall call parent for return value. then,  save object state, method
arguments and return value
later these snapshots can be replayed, the method called with same params and return values compared
these snapshots can be put in any storage (currently I put them in files), and shall include metadata for replay and
reproduction: the called (stubbed) method's name and original request url

about actual implementation:
- this kind of stubbing requires changing method code. in PHP this is possible by the classkit only. so, my stubbing
	depends on ninja's class loading mechanism (that classes defined in the ninja namespace get imported to root
	automatically unless the class is defined in the root namespace by the app). the stubbing is done by defining the
	class in root namespace and overriding its method to be stubbed
- stubbing has to be done by code. it could be automatized on moved to some transport layer, eg. special uri
	params or http headers. personally I think though this would be elegant,
- stubbing implementations limits testing to classes in ninja namespace
- only public methods are testable for now, but protected ones could be opened by reflections
- return values are compared by a simple === operator now which might not be proper for comparing complex values
- object, params, return values are stored by passing through serialize() which has its limitations
- snapshots only include the actual object under test. all dependencies must be referred by the object or present in
	the arguments in order to be included in the snapshot

to run the tests just invoke run.php
note: no webserver needed to run the tests

another note: I understand the oddity and drawbacks of this testing method and I do not suggest using it

PROs:
- it can be a limitation, but the nature of the method enforces black box testing, isolated from global state and data
- no code to be written apart from the initialization
- can effectively test code composition, eg. that all the sub-calls of a method still compose the proper output
CONs:
- maintability - independent class modifications might render the deserialization obsolete (haven't investigated this)
- the required isolation might not let some methods/scenarios to be tested
- the same issue: in spite the external isolation, there is no internal. eg. a method might be run in a subclass, and
	will be played back so too.
- part of the isolation, but method calls must be repeatable
- can only test return values. side effects cannot be checked by code and cannot be captured by stubbing
- productivity - snapshots have to be created by hand, by initializing the tester and calling URLs (or running
	the application otherwise)
- size and data reuse - thought a snapshot is tiny in size, a lot of them are required to increase coverage. each
	method's each scenario requires a dump of the object and this data is not reusable between scenarios, eg. test
	different methods on the same object state
- test coverage is limited to actually captured scenarios, and each scenario has to be run to be captured. this also
	renders TDD usage obsolete
- due to its limited usage (namespace shifting) it's not even worth packing as a module

